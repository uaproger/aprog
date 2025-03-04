#!/usr/bin/env php
<?php

require __DIR__ . '/../autoload.php';

use Symfony\Component\Console\Application;
use Uaproger\Aprog\Console\MakePropertyCommand;

$app = new Application();
$app->add(new MakePropertyCommand());
$app->run();
