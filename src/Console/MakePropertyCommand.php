<?php

namespace Aprog\Console;

use Illuminate\Console\Command;
use Aprog\Property;

class MakePropertyCommand extends Command
{
    protected $signature = 'make:property {name?}';
    protected $description = 'Створення Property';

    public function handle()
    {
        $name = $this->argument('name') ?? 'Example';

        $this->info("Створення Property: `$name`");

        new Property($name);

        $this->newLine();
    }
}
