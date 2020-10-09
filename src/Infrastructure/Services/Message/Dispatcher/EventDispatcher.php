<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Services\Message\Dispatcher;

use InSided\GetOnBoard\Core\Services\Message\Dispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private array $eventListeners = [];

    public function registerListener(string $eventName, callable $eventListener): void
    {
        $this->eventListeners[$eventName] ??= [];
        if (in_array($eventListener, $this->eventListeners[$eventName])) {
            // log duplicate event listener?
            return;
        }

        // TODO: check if listener can handle the event before registering it
        $this->eventListeners[$eventName][] = $eventListener;
    }

    public function dispatch(object $event): void
    {
        $eventName = get_class($event);
        if (!array_key_exists($eventName, $this->eventListeners)) {
            // perhaps log or throw unhandled event
            return;
        }

        foreach ($this->eventListeners[$eventName] as $eventListener) {
            call_user_func($eventListener, $event);
        }
    }
}
