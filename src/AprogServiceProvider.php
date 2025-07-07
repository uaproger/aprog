<?php

namespace Src;

use Illuminate\Support\ServiceProvider;
use Src\Console\MakePropertyCommand;
use Src\Console\MakeServiceCommand;
use Src\Services\AccumulatedErrorsService;

class AprogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakePropertyCommand::class,
            MakeServiceCommand::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/accum.php',
            'accum'
        );

        $this->app->singleton(AccumulatedErrorsService::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/accum.php' => config_path('accum.php'),
        ], 'config');
    }
}
