<?php

namespace Aprog;

use Aprog\Console\MakeAccumulatorCommand;
use Illuminate\Support\ServiceProvider;
use Aprog\Console\MakePropertyCommand;
use Aprog\Console\MakeServiceCommand;
use Aprog\Services\AccumulatedErrorsService;

/**
 * AprogServiceProvider
 *
 * --------------------------------------------------------------------------
 *                      Кастомний сервіс провайдер
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class AprogServiceProvider extends ServiceProvider
{
    /**
     * --- Реєстр ---
     * @return void
     */
    public function register()
    {
        $this->commands([
            MakeAccumulatorCommand::class,
            MakePropertyCommand::class,
            MakeServiceCommand::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/accum.php',
            'accum'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/config/gemini.php',
            'gemini'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/config/telegram.php',
            'telegram'
        );

        $this->app->singleton(AccumulatedErrorsService::class);
    }

    /**
     * --- Boot ---
     * @return void
     */
    public function boot()
    {
        # Публікація конфігів
        $this->publishes([
            __DIR__ . '/config/accum.php' => config_path('accum.php'),
            __DIR__ . '/config/gemini.php' => config_path('gemini.php'),
            __DIR__ . '/config/phone.php' => config_path('phone.php'),
            __DIR__ . '/config/telegram.php' => config_path('telegram.php'),
        ], 'config');

        # Публікація email шаблону
        $this->publishes([
            __DIR__ . '/views/emails/for_developer.blade.php' => resource_path('views/emails/for_developer.blade.php'),
        ], 'views');

        # Реєстрація namespace для переглядів
        $this->loadViewsFrom(__DIR__ . '/views', 'aprog');

        # Публікація міграцій
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
