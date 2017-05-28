<?php
require 'vendor/autoload.php';

use davebarnwell\Event\Mediator;

# Add an anonymous function as a listener
Mediator::attachEvent(
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
        echo 'Class Method ', $p1, ' ', $p2, PHP_EOL;
    }
}

# Create the class and
$someClass = new SomeClass();
# Add a class method as a listener
Mediator::attachEvent(
    'my.event.name',
    array($someClass, 'myListener')
);

# trigger the listeners
Mediator::updateEvent(
    'my.event.name',
    ['Hello', 'World']
);