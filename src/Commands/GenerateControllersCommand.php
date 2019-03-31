<?php

namespace Inani\ControllersGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\RouteCollection;

class GenerateControllersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:controllers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controllers from the routes list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $allRoutes = app('router')->getRoutes();


        $actions = $this->getActionsList($allRoutes);
        // No route are found
        if (count($actions) === 0) {
            $this->warn('No Routes are found');

            return;
        }

        $controllers = array_keys(
            $actions
        );

        $cachedControllers = [];

        $notCreated = true;
        // Loop over all actions
        foreach ($controllers as $controller) {
            $controller = explode('@', $controller);
            // Only parse Controllers that are not parsed before
            if (! in_array($controller[0], $cachedControllers)) {
                $cachedControllers [] = $controller[0];

                if (! class_exists($controller[0])) {
                    \Illuminate\Support\Facades\Artisan::call('make:controller', [
                        'name' => $controller[0],
                    ]);
                    $notCreated = false;
                    $this->info("{$controller[0]} Controller has been created.");
                }
            }
        }

        if($notCreated){
            $this->warn('No controller is needed to be created');
        }
    }

    /**
     * A Helper method
     *
     * @param $routesCollection
     * @return array|mixed
     */
    protected function getActionsList($routesCollection)
    {
        try {
            $reflFoo = new \ReflectionClass(RouteCollection::class);
            $refRoutes = $reflFoo->getProperty('actionList');
            $refRoutes->setAccessible(true);
        } catch (\Exception $e) {
            return [];
        }
        return  $refRoutes->getValue($routesCollection);
    }
}
