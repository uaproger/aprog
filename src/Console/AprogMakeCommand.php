<?php

namespace Src\Console;

use Illuminate\Console\Command;
use Src\Property;

class AprogMakeCommand extends Command
{
    protected $signature = 'aprog:{type?} {name?} {dir?}';
    protected $description = 'Створення властивості для моделі';

    public function handle()
    {
        $type = $this->argument('type') ?? 'property';
        $name = $this->argument('name') ?? 'ExampleProperty';
        $dir = $this->argument('dir') ?? 'app';

        $this->info("Створення властивості: $name");

        if ($type === 'property') {
            new Property($name, $dir);
        }
    }
}