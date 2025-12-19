<?php

namespace Aprog\Console;

use Aprog\Accumulator;
use Aprog\AccumulatorLaravel;
use Illuminate\Console\Command;

class MakeAccumulatorCommand extends Command
{
    protected $signature = 'make:accumulator {name?} {flag}';
    protected $description = 'Створення акумулятора';

    public function handle()
    {
        $name = $this->argument('name') ?? 'Example';
        $flag = $this->argument('flag') ?? null;

        $this->info("Створення акумулятора: `$name`");

        if ($flag === "-l") new AccumulatorLaravel($name);
        else new Accumulator($name);

        $this->newLine();
    }
}
