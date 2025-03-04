<?php

namespace Uaproger;

use Src\Property;

require __DIR__ . '/vendor/autoload.php';

class Aprog
{
    public function __construct($type, $name, $dir)
    {
        $type = explode(':', $type)[1] ?? 'property';
        if ($type === 'property') {
            new Property($name, $dir);
        }
    }
}  new Aprog(($argv[1] ?? 'make:property'), (ucfirst($argv[2]) ?? 'ExampleProperty'), strtolower($argv[3] ?? 'base'));
