<?php

namespace Src\Console;

use Illuminate\Console\Command;
use Src\Property;

class MakePropertyCommand extends Command
{
    protected $signature = 'make:property {name?} {dir?}';
    protected $description = 'Створення Property';

    public function handle()
    {
        $name = $this->argument('name') ?? 'ExampleProperty';
        $dir = $this->argument('dir') ?? 'app';

        $this->info("Створення Property: " . $this->warn("`$name`"));
        $this->newLine();

        new Property($name, $dir);
    }
}
