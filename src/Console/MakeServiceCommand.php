<?php

namespace Aprog\Console;

use Illuminate\Console\Command;
use Aprog\Service;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name?}';
    protected $description = 'Створення сервісу';

    public function handle()
    {
        $name = $this->argument('name') ?? 'Example';

        $this->info("Створення сервісу: `$name`");

        new Service($name);

        $this->newLine();
    }
}
