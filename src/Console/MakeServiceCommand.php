<?php

namespace Src\Console;

use Illuminate\Console\Command;
use Src\Service;

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
