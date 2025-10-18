<?php

namespace App\Core\Plugin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class PluginManager
{
    /**
     * Loaded plugins
     */
    protected static array $plugins = [];
    
    /**
     * Active plugins
     */
    protected static array $activePlugins = [];
    
    /**
     * Plugin instances
     */
    protected static array $instances = [];
    
    /**
     * Plugins directory
     */
    protected static string $pluginsPath;
    
    /**
     * Initialize the plugin manager
     */
    public static function initialize(): void
    {
        self::$pluginsPath = base_path('plugins');
        self::ensurePluginsDirectory();
        self::ensurePluginsTables();
        self::discoverPlugins();
        self::loadActivePlugins();
        self::bootActivePlugins();
    }
    
    /**
     * Ensure plugins directory exists
     */
    protected static function ensurePluginsDirectory(): void
    {
        if (!File::isDirectory(self::$pluginsPath)) {
            File::makeDirectory(self::$pluginsPath, 0755, true);
        }
    }
    
    /**
     * Ensure plugin tables exist
     */
    protected static function ensurePluginsTables(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('plugins')) {
            DB::getSchemaBuilder()->create('plugins', function ($table) {
                $table->id();
                $table->string('slug')->unique();
                $table->string('name');
                $table->string('version');
                $table->enum('status', ['active', 'inactive'])->default('inactive');
                $table->json('settings')->nullable();
                $table->timestamp('activated_at')->nullable();
                $table->timestamps();
                $table->index('status');
            });
        }
        
        if (!DB::getSchemaBuilder()->hasTable('plugin_activations')) {
            DB::getSchemaBuilder()->create('plugin_activations', function ($table) {
                $table->id();
                $table->string('plugin_slug');
                $table->unsignedBigInteger('vendor_id')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->json('settings')->nullable();
                $table->timestamp('activated_at');
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
                $table->unique(['plugin_slug', 'vendor_id']);
                $table->index(['plugin_slug', 'status']);
                $table->index('vendor_id');
            });
        }
    }
    
    /**
     * Discover all available plugins
     */
    protected static function discoverPlugins(): void
    {
        self::$plugins = Cache::remember('discovered_plugins', now()->addHours(1), function () {
            $plugins = [];
            
            if (File::isDirectory(self::$pluginsPath)) {
                $directories = File::directories(self::$pluginsPath);
                
                foreach ($directories as $directory) {
                    $pluginJsonPath = $directory . '/plugin.json';
                    
                    if (File::exists($pluginJsonPath)) {
                        $metadata = json_decode(File::get($pluginJsonPath), true);
                        
                        if ($metadata && isset($metadata['name'])) {
                            $plugins[$metadata['name']] = [
                                'path' => $directory,
                                'metadata' => $metadata
                            ];
                        }
                    }
                }
            }
            
            return $plugins;
        });
    }
    
    /**
     * Load active plugins from database
     */
    protected static function loadActivePlugins(): void
    {
        $activePlugins = DB::table('plugins')
            ->where('status', 'active')
            ->pluck('slug')
            ->toArray();
        
        foreach ($activePlugins as $pluginSlug) {
            if (isset(self::$plugins[$pluginSlug])) {
                self::$activePlugins[$pluginSlug] = self::$plugins[$pluginSlug];
                self::loadPluginClass($pluginSlug);
            }
        }
    }
    
    /**
     * Load a plugin class
     */
    protected static function loadPluginClass(string $pluginSlug): void
    {
        $pluginData = self::$plugins[$pluginSlug] ?? null;
        
        if (!$pluginData) {
            return;
        }
        
        $mainClassPath = $pluginData['path'] . '/src/' . self::getPluginClassName($pluginSlug) . '.php';
        
        if (File::exists($mainClassPath)) {
            require_once $mainClassPath;
            
            $className = self::getPluginFullClassName($pluginSlug);
            
            if (class_exists($className)) {
                self::$instances[$pluginSlug] = new $className($pluginData['path']);
            }
        }
    }
    
    /**
     * Boot active plugins
     */
    protected static function bootActivePlugins(): void
    {
        foreach (self::$instances as $slug => $instance) {
            if ($instance instanceof Plugin && $instance->isActive()) {
                try {
                    $instance->boot();
                } catch (\Exception $e) {
                    \Log::error("Failed to boot plugin {$slug}: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Get all discovered plugins
     */
    public static function getAllPlugins(): Collection
    {
        return collect(self::$plugins)->map(function ($plugin, $slug) {
            $dbPlugin = DB::table('plugins')->where('slug', $slug)->first();
            
            return [
                'slug' => $slug,
                'name' => $plugin['metadata']['displayName'] ?? $slug,
                'description' => $plugin['metadata']['description'] ?? '',
                'version' => $plugin['metadata']['version'] ?? '1.0.0',
                'author' => $plugin['metadata']['author'] ?? 'Unknown',
                'status' => $dbPlugin->status ?? 'inactive',
                'price' => $plugin['metadata']['price'] ?? null,
                'requires' => $plugin['metadata']['requires'] ?? [],
                'provides' => $plugin['metadata']['provides'] ?? [],
            ];
        });
    }
    
    /**
     * Get active plugins
     */
    public static function getActivePlugins(): Collection
    {
        return collect(self::$activePlugins)->map(function ($plugin, $slug) {
            return [
                'slug' => $slug,
                'name' => $plugin['metadata']['displayName'] ?? $slug,
                'version' => $plugin['metadata']['version'] ?? '1.0.0',
            ];
        });
    }
    
    /**
     * Get plugins active for a specific vendor
     */
    public static function getVendorActivePlugins(int $vendorId): Collection
    {
        return DB::table('plugin_activations')
            ->where('vendor_id', $vendorId)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->pluck('plugin_slug');
    }
    
    /**
     * Activate a plugin
     */
    public static function activatePlugin(string $slug, ?int $vendorId = null): bool
    {
        if (!isset(self::$plugins[$slug])) {
            throw new \Exception("Plugin {$slug} not found");
        }
        
        // Load plugin if not loaded
        if (!isset(self::$instances[$slug])) {
            self::loadPluginClass($slug);
        }
        
        $plugin = self::$instances[$slug] ?? null;
        
        if (!$plugin) {
            throw new \Exception("Failed to load plugin {$slug}");
        }
        
        // Check dependencies
        $missingDependencies = $plugin->checkDependencies();
        if (!empty($missingDependencies)) {
            throw new \Exception("Missing dependencies: " . implode(', ', $missingDependencies));
        }
        
        try {
            // Activate plugin
            $plugin->activate();
            
            // If vendor-specific activation
            if ($vendorId) {
                DB::table('plugin_activations')->updateOrInsert(
                    [
                        'plugin_slug' => $slug,
                        'vendor_id' => $vendorId
                    ],
                    [
                        'status' => 'active',
                        'activated_at' => now(),
                        'updated_at' => now()
                    ]
                );
            } else {
                // Global activation
                self::$activePlugins[$slug] = self::$plugins[$slug];
            }
            
            // Clear cache
            Cache::forget('discovered_plugins');
            Cache::forget('active_plugins');
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error("Failed to activate plugin {$slug}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deactivate a plugin
     */
    public static function deactivatePlugin(string $slug, ?int $vendorId = null): bool
    {
        if (!isset(self::$instances[$slug])) {
            return false;
        }
        
        $plugin = self::$instances[$slug];
        
        try {
            // Deactivate plugin
            $plugin->deactivate();
            
            if ($vendorId) {
                DB::table('plugin_activations')
                    ->where('plugin_slug', $slug)
                    ->where('vendor_id', $vendorId)
                    ->update(['status' => 'inactive', 'updated_at' => now()]);
            } else {
                unset(self::$activePlugins[$slug]);
            }
            
            // Clear cache
            Cache::forget('discovered_plugins');
            Cache::forget('active_plugins');
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error("Failed to deactivate plugin {$slug}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Uninstall a plugin
     */
    public static function uninstallPlugin(string $slug): bool
    {
        try {
            // First deactivate if active
            if (isset(self::$activePlugins[$slug])) {
                self::deactivatePlugin($slug);
            }
            
            // Call uninstall if instance exists
            if (isset(self::$instances[$slug])) {
                self::$instances[$slug]->uninstall();
                unset(self::$instances[$slug]);
            }
            
            // Remove from discovered plugins
            unset(self::$plugins[$slug]);
            
            // Delete plugin directory
            $pluginPath = self::$pluginsPath . '/' . $slug;
            if (File::isDirectory($pluginPath)) {
                File::deleteDirectory($pluginPath);
            }
            
            // Clear cache
            Cache::forget('discovered_plugins');
            Cache::forget('active_plugins');
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error("Failed to uninstall plugin {$slug}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if a plugin is active
     */
    public static function isPluginActive(string $slug): bool
    {
        return isset(self::$activePlugins[$slug]);
    }
    
    /**
     * Check if a plugin is active for vendor
     */
    public static function isPluginActiveForVendor(string $slug, int $vendorId): bool
    {
        if (!self::isPluginActive($slug)) {
            return false;
        }
        
        return DB::table('plugin_activations')
            ->where('plugin_slug', $slug)
            ->where('vendor_id', $vendorId)
            ->where('status', 'active')
            ->exists();
    }
    
    /**
     * Get plugin instance
     */
    public static function getPlugin(string $slug): ?Plugin
    {
        return self::$instances[$slug] ?? null;
    }
    
    /**
     * Get plugin class name from slug
     */
    protected static function getPluginClassName(string $slug): string
    {
        $parts = explode('-', $slug);
        $className = '';
        
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }
        
        return $className . 'Plugin';
    }
    
    /**
     * Get full plugin class name with namespace
     */
    protected static function getPluginFullClassName(string $slug): string
    {
        $className = self::getPluginClassName($slug);
        $namespace = str_replace('-', '', ucwords($slug, '-'));
        
        return "\\Plugins\\{$namespace}\\{$className}";
    }
    
    /**
     * Execute hook for active plugins
     */
    public static function executeHook(string $hook, ...$args)
    {
        $results = [];
        
        foreach (self::$instances as $plugin) {
            if ($plugin instanceof Plugin && $plugin->isActive()) {
                if (method_exists($plugin, $hook)) {
                    $results[] = $plugin->{$hook}(...$args);
                }
            }
        }
        
        return $results;
    }
}