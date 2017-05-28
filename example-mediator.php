<?php
require 'vendor/autoload.php';

use davebarnwell\Event\Mediator;

# Add an anonymous function as an observer
Mediator::attachEventObserver(
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
# Add a class method as an observer
Mediator::attachEventObserver(
    'my.event.name',
    array($someClass, 'myListener')
);

# notify the observers
Mediator::updateEventObservers(
    'my.event.name',
    ['Hello', 'World']
);