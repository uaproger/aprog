<?php

namespace Aprog\Console;

use Aprog\Enum;
use Illuminate\Console\Command;

class MakeEnumCommand extends Command
{
    protected $signature = 'make:enum {name=ExampleEnum} {--i}';
    protected $description = 'Створення Enum';

    public function handle()
    {
        $name = $this->argument('name');
        $isInteger = $this->option('i');

        $type = 'string';
        if ($isInteger) $type = 'int';

        $this->info("Створення Enum: `$name`");

        new Enum($name, $type);
    }
}
