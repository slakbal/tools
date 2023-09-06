<?php

namespace Slakbal\Tools;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ToolsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/tools.php' => config_path('tools.php'),
        ], 'tools.config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        //Load the config before anything else
        $this->mergeConfigFrom(__DIR__ . '/../config/tools.php', 'tools');
    }
}
