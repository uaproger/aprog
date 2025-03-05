<?php

namespace Src;

use Illuminate\Support\ServiceProvider;
use Src\Console\MakePropertyCommand;
use Src\Console\MakeServiceCommand;

class AprogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakePropertyCommand::class,
            MakeServiceCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
}
