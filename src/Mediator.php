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
    private static $events = [];

    /**
     * @var [string]bool
     */
    private static $propagationStopped = [];


    /**
     * trigger all listeners for the specified event, any previous stop on
     * this events propagation is cleared before the registered listeners are called.
     *
     * @param string $eventName
     * @param array  $args
     */
    public static function trigger(string $eventName, $args = array())
    {
        if (isset(self::$events[$eventName])) {
            self::clearStopPropagation($eventName);
            foreach (self::$events[$eventName] as $listener) {
                call_user_func_array($listener, $args);
                if (self::isPropagationStopped($eventName)) {
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
    public static function countAllListeners(): array
    {
        $registeredEvents = [];
        foreach (self::$events as $eventName) {
            $registeredEvents[$eventName] = count(self::$events[$eventName]);
        }
        return $registeredEvents;
    }

    /**
     * @param string $eventName
     *
     * @return int
     */
    public static function countListeners(string $eventName): int
    {
        return isset(self::$events[$eventName]) ? count(self::$events[$eventName]) : 0;
    }

    /**
     * When called in an event listener it stops and subsequent event listeners
     * being called for the current event.
     *
     * @param string $eventName
     */
    public static function stopPropagation(string $eventName)
    {
        self::$propagationStopped[$eventName] = true;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public static function isPropagationStopped(string $eventName): bool
    {
        return isset(self::$propagationStopped[$eventName]) ? self::$propagationStopped[$eventName] : false;
    }

    /**
     * @param string $eventName
     */
    public static function clearStopPropagation(string $eventName)
    {
        self::$propagationStopped[$eventName] = false;
    }

    /**
     * @param string   $eventName
     * @param callable $listener
     */
    public static function addListener(string $eventName, callable $listener)
    {
        self::$events[$eventName][] = $listener;
    }

    /**
     * @param string   $eventName
     * @param callable $listenerToRemove
     *
     * @return bool
     */
    public static function removeListener(string $eventName, callable $listenerToRemove): bool
    {
        if (isset(self::$events[$eventName])) {
            foreach (self::$events[$eventName] as $i => $listener) {
                if ($listener == $listenerToRemove) {
                    array_splice(self::$events, $i, 1);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public static function removeAllListeners(string $eventName): bool
    {
        if (isset(self::$events[$eventName])) {
            self::$events[$eventName] = [];
            return true;
        }
        return false;
    }
}