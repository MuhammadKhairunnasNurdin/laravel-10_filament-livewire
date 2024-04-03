<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
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
        /**
         * For brevity in this guide, we will disable Laravel's mass
         * assignment protection. Filament only saves valid data to models
         * so the models can be unguarded safely. To unguard all Laravel
         * models at once, add Model::unguard() to the boot() method of
         * app/Providers/AppServiceProvider.php:
         */
        Model::unguard();
    }
}
