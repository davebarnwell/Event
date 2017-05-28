<?php
require 'vendor/autoload.php';

use davebarnwell\Event\Observable;

# A class purely as an example
class SomeClass extends Observable
{
    public function myListener($p1, $p2)
    {
        echo 'Class Method ', $p1, ' ', $p2, PHP_EOL;
    }
}

# Create the class and
$someClass = new SomeClass();
# Add a class method as a listener
$someClass->attachEvent(
    'event_name',
    array($someClass, 'myListener')
);

# Add an anonymous function as a listener
$someClass->attachEvent(
    'event_name',
    function ($p1, $p2) {
        echo 'Anonymous func ', $p1, ' ', $p2, PHP_EOL;
    }
);

# trigger the listeners
$someClass->updateEvent(
    'event_name',
    ['Hello', 'World']
);