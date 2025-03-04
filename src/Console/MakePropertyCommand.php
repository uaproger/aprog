<?php

namespace Src\Console;

use Illuminate\Console\Command;
use Src\Property;

class MakePropertyCommand extends Command
{
    protected $signature = 'make:property {name?} {dir?}';
    protected $description = 'Створення властивості для моделі';

    public function handle()
    {
        $name = $this->argument('name') ?? 'ExampleProperty';
        $dir = $this->argument('dir') ?? 'app';

        $this->info("Створення властивості: $name");
        $this->newLine();

        new Property($name, $dir);

        $this->newLine();
    }
}