<?php

namespace MylesDuncanKing\SimpleMigration;

use Illuminate\Support\ServiceProvider;

class SimpleMigrationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->offerPublishing();

        $this->registerCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/simplemigration.php',
            'simplemigration'
        );
    }

    protected function offerPublishing()
    {
        if (! function_exists('config_path')) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/simplemigration.php' => config_path('simplemigration.php'),
        ], 'simplemigration');
    }

    protected function registerCommands()
    {
        $this->commands([
            Commands\CreateSimpleMigration::class,
        ]);
    }
}
