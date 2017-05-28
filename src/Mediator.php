<?php

namespace davebarnwell\Event;

/**
 * Class Mediator
 *
 * Implements a single central event Mediator
 *
 * @package davebarnwell\Event
 */
class Mediator
{
    /**
     * @var [string][]callable
     */
    private static $eventListeners = [];

    /**
     * @var [string]bool
     */
    private static $eventPropagationStopped = [];


    /**
     * trigger all listeners for the specified event, any previous stop on
     * this events propagation is cleared before the registered listeners are called.
     *
     * @param string $eventName
     * @param array  $args
     */
    public static function updateEvent(string $eventName, $args = array())
    {
        if (isset(self::$eventListeners[$eventName])) {
            self::allowEventPropagation($eventName);
            foreach (self::$eventListeners[$eventName] as $listener) {
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
    public static function countAllObservers(): array
    {
        $registeredEvents = [];
        foreach (self::$eventListeners as $eventName) {
            $registeredEvents[$eventName] = count(self::$eventListeners[$eventName]);
        }
        return $registeredEvents;
    }

    /**
     * @param string $eventName
     *
     * @return int
     */
    public static function countObservers(string $eventName): int
    {
        return isset(self::$eventListeners[$eventName]) ? count(self::$eventListeners[$eventName]) : 0;
    }

    /**
     * When called in an event listener it stops and subsequent event listeners
     * being called for the current event.
     *
     * @param string $eventName
     */
    public static function stopEventPropagation(string $eventName)
    {
        self::$eventPropagationStopped[$eventName] = true;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public static function isEventPropagationStopped(string $eventName): bool
    {
        return isset(self::$eventPropagationStopped[$eventName]) ? self::$eventPropagationStopped[$eventName] : false;
    }

    /**
     * @param string $eventName
     */
    public static function allowEventPropagation(string $eventName)
    {
        self::$eventPropagationStopped[$eventName] = false;
    }

    /**
     * @param string   $eventName
     * @param callable $listener
     */
    public static function attachEvent(string $eventName, callable $listener)
    {
        self::$eventListeners[$eventName][] = $listener;
    }

    /**
     * @param string   $eventName
     * @param callable $listenerToRemove
     *
     * @return bool
     */
    public static function detachEvent(string $eventName, callable $listenerToRemove): bool
    {
        if (isset(self::$eventListeners[$eventName])) {
            $key = array_search($listenerToRemove,self::$eventListeners[$eventName], true);
            if($key){
                unset(self::$eventListeners[$eventName][$key]);
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
    public static function detachAllObservers(string $eventName): bool
    {
        if (isset(self::$eventListeners[$eventName])) {
            self::$eventListeners[$eventName] = [];
            return true;
        }
        return false;
    }
}