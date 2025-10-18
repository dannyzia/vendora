<?php

namespace App\Core\Plugin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

abstract class Plugin
{
    /**
     * Plugin metadata from plugin.json
     */
    protected array $metadata = [];
    
    /**
     * Plugin directory path
     */
    protected string $path;
    
    /**
     * Plugin unique identifier
     */
    protected string $slug;
    
    /**
     * Plugin activation status
     */
    protected bool $isActive = false;
    
    /**
     * Plugin settings
     */
    protected array $settings = [];
    
    /**
     * Constructor
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->loadMetadata();
        $this->slug = $this->metadata['name'] ?? basename($path);
        $this->loadSettings();
    }
    
    /**
     * Boot method - called when plugin is active
     * Override this in your plugin
     */
    abstract public function boot(): void;
    
    /**
     * Called when plugin is activated
     */
    public function activate(): void
    {
        // Override in plugin if needed
        $this->createTables();
        $this->seedDefaultData();
        $this->cacheSettings();
        
        DB::table('plugins')->updateOrInsert(
            ['slug' => $this->slug],
            [
                'name' => $this->metadata['displayName'] ?? $this->slug,
                'version' => $this->metadata['version'] ?? '1.0.0',
                'status' => 'active',
                'settings' => json_encode($this->settings),
                'activated_at' => now(),
                'updated_at' => now()
            ]
        );
    }
    
    /**
     * Called when plugin is deactivated
     */
    public function deactivate(): void
    {
        // Override in plugin if needed
        $this->clearCache();
        
        DB::table('plugins')
            ->where('slug', $this->slug)
            ->update(['status' => 'inactive', 'updated_at' => now()]);
    }
    
    /**
     * Called when plugin is uninstalled
     */
    public function uninstall(): void
    {
        // Override in plugin if needed
        $this->dropTables();
        $this->deleteSettings();
        $this->deleteMediaFiles();
        
        DB::table('plugins')->where('slug', $this->slug)->delete();
        DB::table('plugin_activations')->where('plugin_slug', $this->slug)->delete();
    }
    
    /**
     * Load plugin metadata from plugin.json
     */
    protected function loadMetadata(): void
    {
        $metadataFile = $this->path . '/plugin.json';
        
        if (File::exists($metadataFile)) {
            $this->metadata = json_decode(File::get($metadataFile), true) ?? [];
        }
    }
    
    /**
     * Load plugin settings
     */
    protected function loadSettings(): void
    {
        $plugin = DB::table('plugins')->where('slug', $this->slug)->first();
        
        if ($plugin && $plugin->settings) {
            $this->settings = json_decode($plugin->settings, true) ?? [];
        }
    }
    
    /**
     * Get plugin metadata
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
    
    /**
     * Get plugin slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
    
    /**
     * Get plugin path
     */
    public function getPath(): string
    {
        return $this->path;
    }
    
    /**
     * Get plugin version
     */
    public function getVersion(): string
    {
        return $this->metadata['version'] ?? '1.0.0';
    }
    
    /**
     * Check if plugin is active
     */
    public function isActive(): bool
    {
        if (!$this->isActive) {
            $plugin = DB::table('plugins')
                ->where('slug', $this->slug)
                ->where('status', 'active')
                ->exists();
            
            $this->isActive = $plugin;
        }
        
        return $this->isActive;
    }
    
    /**
     * Check if plugin is active for specific vendor
     */
    public function isActiveForVendor(int $vendorId): bool
    {
        return DB::table('plugin_activations')
            ->where('plugin_slug', $this->slug)
            ->where('vendor_id', $vendorId)
            ->where('status', 'active')
            ->exists();
    }
    
    /**
     * Get plugin setting
     */
    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }
    
    /**
     * Set plugin setting
     */
    public function setSetting(string $key, $value): void
    {
        $this->settings[$key] = $value;
        
        DB::table('plugins')
            ->where('slug', $this->slug)
            ->update([
                'settings' => json_encode($this->settings),
                'updated_at' => now()
            ]);
        
        $this->cacheSettings();
    }
    
    /**
     * Get all settings
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
    
    /**
     * Get asset URL
     */
    public function getAssetUrl(string $path): string
    {
        return asset('plugins/' . $this->slug . '/assets/' . ltrim($path, '/'));
    }
    
    /**
     * Get view path
     */
    public function getViewPath(string $view): string
    {
        return $this->path . '/resources/views/' . str_replace('.', '/', $view) . '.blade.php';
    }
    
    /**
     * Check plugin dependencies
     */
    public function checkDependencies(): array
    {
        $missing = [];
        
        if (isset($this->metadata['requires']['plugins'])) {
            foreach ($this->metadata['requires']['plugins'] as $requiredPlugin) {
                if (!PluginManager::isPluginActive($requiredPlugin)) {
                    $missing[] = $requiredPlugin;
                }
            }
        }
        
        return $missing;
    }
    
    /**
     * Create plugin tables
     */
    protected function createTables(): void
    {
        $migrationPath = $this->path . '/database/migrations';
        
        if (File::isDirectory($migrationPath)) {
            $migrations = File::files($migrationPath);
            
            foreach ($migrations as $migration) {
                require_once $migration->getPathname();
                
                $className = $this->getMigrationClassName($migration->getFilename());
                if (class_exists($className)) {
                    (new $className)->up();
                }
            }
        }
    }
    
    /**
     * Drop plugin tables
     */
    protected function dropTables(): void
    {
        $migrationPath = $this->path . '/database/migrations';
        
        if (File::isDirectory($migrationPath)) {
            $migrations = array_reverse(File::files($migrationPath));
            
            foreach ($migrations as $migration) {
                require_once $migration->getPathname();
                
                $className = $this->getMigrationClassName($migration->getFilename());
                if (class_exists($className)) {
                    (new $className)->down();
                }
            }
        }
    }
    
    /**
     * Seed default data
     */
    protected function seedDefaultData(): void
    {
        // Override in plugin if needed
    }
    
    /**
     * Delete plugin settings
     */
    protected function deleteSettings(): void
    {
        DB::table('settings')->where('group', 'plugin_' . $this->slug)->delete();
    }
    
    /**
     * Delete plugin media files
     */
    protected function deleteMediaFiles(): void
    {
        $mediaPath = storage_path('app/public/plugins/' . $this->slug);
        
        if (File::isDirectory($mediaPath)) {
            File::deleteDirectory($mediaPath);
        }
    }
    
    /**
     * Cache plugin settings
     */
    protected function cacheSettings(): void
    {
        Cache::put('plugin_settings_' . $this->slug, $this->settings, now()->addDays(7));
    }
    
    /**
     * Clear plugin cache
     */
    protected function clearCache(): void
    {
        Cache::forget('plugin_settings_' . $this->slug);
    }
    
    /**
     * Get migration class name from filename
     */
    private function getMigrationClassName(string $filename): string
    {
        $name = str_replace('.php', '', $filename);
        $parts = explode('_', $name);
        array_shift($parts); // Remove timestamp parts
        array_shift($parts);
        array_shift($parts);
        array_shift($parts);
        
        $className = '';
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }
        
        return $className;
    }
    
    /**
     * Log plugin activity
     */
    protected function log(string $message, string $level = 'info'): void
    {
        \Log::channel('plugins')->{$level}("[{$this->slug}] {$message}");
    }
}