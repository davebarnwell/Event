<?php
use davebarnwell\Event\Mediator;

Mediator::addListener(
    'my.event.name',
    function ($p1, $p2) {
        echo 'Anonymous func', $p1, $p2, PHP_EOL;
    });

class SomeClass
{
    public function someLisenter($p1, $p2)
    {
        echo 'class method', $p1, $p2, PHP_EOL;
    }

    public function addListeners()
    {
        Mediator::addListener(
            'my.event.name',
            array($this, 'myEventNameListener')
        );
    }
}

$someClass = new SomeClass();
$someClass->addListeners();


Mediator::trigger('my.event.name', ['Hello', 'World']);