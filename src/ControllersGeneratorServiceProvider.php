<?php
namespace Inani\ControllersGenerator;

use Illuminate\Support\ServiceProvider;
use Inani\ControllersGenerator\Commands\GenerateControllersCommand;

class ControllersGeneratorServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot What is needed
     *
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateControllersCommand::class,
            ]);
        }
    }
}
