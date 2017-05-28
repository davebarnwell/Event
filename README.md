# Event

PHP Event Mediator


## usage

Example that shows using an anonymous function and a class method as event listeners, and
passing a couple of parameters to the listener. Listeners can be any callable.

```php
/* You Need to autoload or include class directly before using it */

use davebarnwell\Event\Mediator;

Mediator::addListener(
    'my.event.name',
    function ($p1, $p2) {
        echo 'Anonymous func ', $p1, ' ', $p2, PHP_EOL;
    });

class SomeClass
{
    public function myListener($p1, $p2)
    {
        echo 'class method ', $p1, ' ', $p2, PHP_EOL;
    }

    public function addListeners()
    {
        Mediator::addListener(
            'my.event.name',
            array($this, 'myListener')
        );
    }
}

$someClass = new SomeClass();
$someClass->addListeners();


Mediator::trigger('my.event.name', ['Hello', 'World']);

/*
Outputs:-

Anonymous func Hello World
class method Hello World

*/
```