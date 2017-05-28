<?php
require 'vendor/autoload.php';

use davebarnwell\Event\Mediator;

# Add an anonymous function as a listener
Mediator::addListener(
    'my.event.name',
    function ($p1, $p2) {
        echo 'Anonymous func ', $p1, ' ', $p2, PHP_EOL;
    }
);

# A class purely as an example
class SomeClass
{
    public function myListener($p1, $p2)
    {
        echo 'class method ', $p1, ' ', $p2, PHP_EOL;
    }
}

# Create the class and
$someClass = new SomeClass();
# Add a class method as a listener
Mediator::addListener(
    'my.event.name',
    array($someClass, 'myListener')
);

# trigger the listeners
Mediator::trigger(
    'my.event.name',
    ['Hello', 'World']
);