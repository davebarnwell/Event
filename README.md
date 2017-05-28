# Event

PHP Event Mediator


## usage

Example that shows using an anonymous function and a class method as event listeners, and
passing a couple of parameters to the listener. Listeners can be any callable.

```php
/* You Need to autoload or include class directly before using it */

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

/*
Outputs:-

Anonymous func Hello World
class method Hello World

*/
```