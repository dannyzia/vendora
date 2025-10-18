<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Plugin\PluginManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register helper functions
        require_once app_path('Helpers/hooks.php');
        
        // Register plugin manager as singleton
        $this->app->singleton('plugin.manager', function () {
            return new PluginManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Initialize plugin system after all providers are loaded
        $this->app->booted(function () {
            PluginManager::initialize();
        });
        
        // Register Blade directives for plugins
        $this->registerBladeDirectives();
        
        // Register plugin routes
        $this->registerPluginRoutes();
        
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }
    
    /**
     * Register Blade directives
     */
    protected function registerBladeDirectives(): void
    {
        // @hook directive for action hooks in blade templates
        Blade::directive('hook', function ($expression) {
            return "<?php do_action({$expression}); ?>";
        });
        
        // @filter directive for filter hooks in blade templates
        Blade::directive('filter', function ($expression) {
            return "<?php echo apply_filters({$expression}); ?>";
        });
        
        // @ifplugin directive to check if plugin is active
        Blade::directive('ifplugin', function ($expression) {
            return "<?php if(\\App\\Core\\Plugin\\PluginManager::isPluginActive({$expression})): ?>";
        });
        
        Blade::directive('endifplugin', function () {
            return "<?php endif; ?>";
        });
        
        // @ifvendorplugin directive to check if plugin is active for vendor
        Blade::directive('ifvendorplugin', function ($expression) {
            list($plugin, $vendorId) = explode(',', $expression);
            return "<?php if(\\App\\Core\\Plugin\\PluginManager::isPluginActiveForVendor({$plugin}, {$vendorId})): ?>";
        });
        
        Blade::directive('endifvendorplugin', function () {
            return "<?php endif; ?>";
        });
    }
    
    /**
     * Register plugin routes
     */
    protected function registerPluginRoutes(): void
    {
        // Plugin admin routes
        Route::middleware(['web', 'auth', 'admin'])->prefix('admin/plugins')->group(function () {
            Route::get('/', [\\App\\Http\\Controllers\\Admin\\PluginController::class, 'index'])->name('admin.plugins.index');
            Route::post('/{plugin}/activate', [\\App\\Http\\Controllers\\Admin\\PluginController::class, 'activate'])->name('admin.plugins.activate');
            Route::post('/{plugin}/deactivate', [\\App\\Http\\Controllers\\Admin\\PluginController::class, 'deactivate'])->name('admin.plugins.deactivate');
            Route::delete('/{plugin}', [\\App\\Http\\Controllers\\Admin\\PluginController::class, 'uninstall'])->name('admin.plugins.uninstall');
            Route::get('/{plugin}/settings', [\\App\\Http\\Controllers\\Admin\\PluginController::class, 'settings'])->name('admin.plugins.settings');
            Route::post('/{plugin}/settings', [\\App\\Http\\Controllers\\Admin\\PluginController::class, 'updateSettings'])->name('admin.plugins.settings.update');
        });
        
        // Vendor plugin routes
        Route::middleware(['web', 'auth', 'vendor'])->prefix('vendor/plugins')->group(function () {
            Route::get('/', [\\App\\Http\\Controllers\\Vendor\\PluginController::class, 'index'])->name('vendor.plugins.index');
            Route::post('/{plugin}/activate', [\\App\\Http\\Controllers\\Vendor\\PluginController::class, 'activate'])->name('vendor.plugins.activate');
            Route::post('/{plugin}/deactivate', [\\App\\Http\\Controllers\\Vendor\\PluginController::class, 'deactivate'])->name('vendor.plugins.deactivate');
            Route::get('/{plugin}/settings', [\\App\\Http\\Controllers\\Vendor\\PluginController::class, 'settings'])->name('vendor.plugins.settings');
            Route::post('/{plugin}/settings', [\\App\\Http\\Controllers\\Vendor\\PluginController::class, 'updateSettings'])->name('vendor.plugins.settings.update');
        });
    }
    
    /**
     * Register console commands
     */
    protected function registerCommands(): void
    {
        $this->commands([
            \\App\\Console\\Commands\\PluginCreateCommand::class,
            \\App\\Console\\Commands\\PluginListCommand::class,
            \\App\\Console\\Commands\\PluginActivateCommand::class,
            \\App\\Console\\Commands\\PluginDeactivateCommand::class,
            \\App\\Console\\Commands\\PluginInstallCommand::class,
            \\App\\Console\\Commands\\PluginUninstallCommand::class,
        ]);
    }
}