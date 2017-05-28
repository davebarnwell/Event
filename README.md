# Event

PHP Event supports two kinds of event handlers, Observer (**Observable**) & **Mediator**, both are about watching a
subject object but differ whether the event is updated (triggered) at an an instance [Observable](src/Observable.php)
or global level [Mediator](src/Mediator.php). When an event is updated in either instance a variable number of arguments
can be passed to the observers, it's up to you to define whats required, but can include a reference to the subject
object for instance which can be very useful.

- **Mediator**: when you want to have a central event handler, event names are application wide, so it's good to use
  dotted event names, eg. *album.track.added* when an event is updated (fired), if the subject that updates the event
  needs to be accessible by the observer, you can pass a reference to it in the args of the update call from the subject.
- **Observer**: (Observable) when you want individual objects to own the events, this distributes events across class
  instances and can be harder to debug when there are a lot of class instances with different listeners attached, but it
  can give huge flexibility when used wisely. If you need instance based Observers please also check if PHP Spl might
  work for you first, as standard libs are always preferable, [SplObsever](http://php.net/manual/en/class.splobserver.php) / 
  [SplSubject](http://php.net/manual/en/class.splsubject.php) implement a different but similar interface to Observable.

I personally believe the Mediator pattern is the easiest to debug and work with because one object has visibility of all
events in the system, and when events always include as their first argument to Observers the subject object the use
cases of the per instance Observer pattern are greatly reduced.

## Mediator usage:-

[Example](example-mediator.php) that shows using an anonymous function and a class method as event listeners, and
passing a couple of parameters to the listener. Listeners can be any callable.

```php
/* You Need to autoload or include class directly before using it */

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

/*
Outputs:-

Anonymous func Hello World
class method Hello World

*/
```

## Observable usage:-

[Example](example-obserable.php) that shows using an anonymous function and a class method as event listeners, and
passing a couple of parameters to the listener. Listeners can be any callable.

```php
/* You Need to autoload or include class directly before using it */

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
# Add a class method as an observer
$someClass->attachEventObserver(
    'event_name',
    array($someClass, 'myListener')
);

# Add an anonymous function as an observer
$someClass->attachEventObserver(
    'event_name',
    function ($p1, $p2) {
        echo 'Anonymous func ', $p1, ' ', $p2, PHP_EOL;
    }
);

# notify the observers
$someClass->updateEventObservers(
    'event_name',
    ['Hello', 'World']
);

/*
Outputs:-

Anonymous func Hello World
class method Hello World

*/
```