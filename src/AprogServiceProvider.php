<?php

namespace Aprog;

use Illuminate\Support\ServiceProvider;
use Aprog\Console\MakePropertyCommand;
use Aprog\Console\MakeServiceCommand;
use Aprog\Services\AccumulatedErrorsService;

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
