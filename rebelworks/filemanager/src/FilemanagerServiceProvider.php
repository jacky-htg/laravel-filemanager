<?php

namespace Rebelworks\Filemanager;

use Illuminate\Support\ServiceProvider;

class FilemanagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/filemanager.php' => config_path('filemanager.php'),
        ]);
        
        $this->loadViewsFrom(__DIR__.'/views', 'filemanager');
        $this->publishes([
            __DIR__.'/views'  => base_path('resources/views/rebelworks/filemanager'),
        ], 'filemanager');  
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        
        $this->mergeConfigFrom(
            __DIR__.'/config/filemanager.php', 'filemanager'
        );
    }
}
