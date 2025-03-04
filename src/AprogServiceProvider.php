<?php

namespace Src;

use Illuminate\Support\ServiceProvider;
use Src\Console\MakePropertyCommand;

class AprogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakePropertyCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
}
