<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'digital_product' => \Plugins\DigitalProducts\Models\DigitalProduct::class,
            'physical_product' => \App\Models\PhysicalProduct::class,
            'service_product' => \Plugins\ServiceProducts\Models\ServiceProduct::class,
        ]);
    }
}
