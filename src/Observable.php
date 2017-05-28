<?php

namespace davebarnwell\Event;

/**
 * Class Observable
 *
 * Implements a per class instance events
 *
 * @package davebarnwell\Observable
 */
abstract class Observable
{
    /**
     * @var [string][]callable
     */
    private $eventListeners = [];

    /**
     * @var [string]bool
     */
    private $eventPropagationStopped = [];


    /**
     * trigger all listeners for the specified event, any previous stop on
     * this events propagation is cleared before the registered listeners are called.
     *
     * @param string $eventName
     * @param array  $args
     */
    public function updateEvent(string $eventName, $args = array())
    {
        if (isset($this->eventListeners[$eventName])) {
            self::allowEventPropagation($eventName);
            foreach ($this->eventListeners[$eventName] as $listener) {
                call_user_func_array($listener, $args);
                if (self::isEventPropagationStopped($eventName)) {
                    break;
                }
            }
        }

    }

    /**
     * return an associative array indexed by event name of the number of listeners
     * registered for each named event
     *
     * @return array
     */
    public function countAllObservers(): array
    {
        $registeredEvents = [];
        foreach ($this->eventListeners as $eventName) {
            $registeredEvents[$eventName] = count($this->eventListeners[$eventName]);
        }
        return $registeredEvents;
    }

    /**
     * @param string $eventName
     *
     * @return int
     */
    public function countObservers(string $eventName): int
    {
        return isset($this->eventListeners[$eventName]) ? count($this->eventListeners[$eventName]) : 0;
    }

    /**
     * When called in an event listener it stops and subsequent event listeners
     * being called for the current event.
     *
     * @param string $eventName
     */
    public function stopEventPropagation(string $eventName)
    {
        $this->eventPropagationStopped[$eventName] = true;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function isEventPropagationStopped(string $eventName): bool
    {
        return isset($this->eventPropagationStopped[$eventName]) ? $this->eventPropagationStopped[$eventName] : false;
    }

    /**
     * @param string $eventName
     */
    public function allowEventPropagation(string $eventName)
    {
        $this->eventPropagationStopped[$eventName] = false;
    }

    /**
     * @param string   $eventName
     * @param callable $listener
     */
    public function attachEvent(string $eventName, callable $listener)
    {
        $this->eventListeners[$eventName][] = $listener;
    }

    /**
     * @param string   $eventName
     * @param callable $listenerToRemove
     *
     * @return bool
     */
    public function detachEvent(string $eventName, callable $listenerToRemove): bool
    {
        if (isset($this->eventListeners[$eventName])) {
            $key = array_search($listenerToRemove,$this->eventListeners[$eventName], true);
            if($key){
                unset($this->eventListeners[$eventName][$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function detachAllObservers(string $eventName): bool
    {
        if (isset($this->eventListeners[$eventName])) {
            $this->eventListeners[$eventName] = [];
            return true;
        }
        return false;
    }
}