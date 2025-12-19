<?php

namespace Aprog\Console;

use Aprog\Accumulator;
use Aprog\AccumulatorLaravel;
use Illuminate\Console\Command;

class MakeAccumulatorCommand extends Command
{
    protected $signature = 'make:accumulator {name=Example} {--l}';
    protected $description = 'Створення акумулятора';

    public function handle()
    {
        $name = $this->argument('name');
        $isLaravel = $this->option('l');

        $this->info("Створення акумулятора: `$name`");

        if ($isLaravel) {
            new AccumulatorLaravel($name);
        } else {
            new Accumulator($name);
        }
    }
}
