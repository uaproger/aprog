<?php

namespace Src;

use Illuminate\Support\ServiceProvider;
use Src\Console\AprogMakeCommand;

class AprogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            AprogMakeCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
}
