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
        $name = $this->argument('name') ?? 'Example';
        $dir = $this->argument('dir') ?? 'app';

        $this->info("Створення Property: `$name`");

        new Property($name, $dir);

        $this->newLine();
    }
}
