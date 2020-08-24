<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Artisan;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->clearCache();
        // these are to refresh configs and environment variables, since $app has loaded cache before it was cleared
        $app->make(LoadEnvironmentVariables::class)->bootstrap($app);
        $app->make(LoadConfiguration::class)->bootstrap($app);

        return $app;
    }

    /**
     * Clears Laravel Cache.
     */
    protected function clearCache(): void
    {
        $commands = ['clear-compiled', 'cache:clear', 'view:clear', 'config:clear', 'route:clear'];
        foreach ($commands as $command) {
            Artisan::call($command);
        }
    }
}
