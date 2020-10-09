<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Services\Message\Dispatcher;

use InSided\GetOnBoard\Infrastructure\Services\Message\Dispatcher\EventDispatcher;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testEventListenerGetsCalled()
    {
        $event = new DummyEvent();

        $eventListener = $this->createMock(DummyEventListener::class);
        $eventListener->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($event));

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->registerListener(DummyEvent::class, [$eventListener, 'handle']);
        $eventDispatcher->dispatch($event);
    }

    public function testEventListenerDoesNotGetCalledWithTheWrongEvent()
    {
        $event = new DummyEvent();

        $eventListener = $this->createMock(DummyEventListener::class);
        $eventListener->expects($this->exactly(0))
            ->method('handle');

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->registerListener(SecondDummyEvent::class, [$eventListener, 'handle']);
        $eventDispatcher->dispatch($event);
    }

    public function testEventListenerIsNotRegisteredMultipleTimes()
    {
        $event = new DummyEvent();

        $eventListener = $this->createMock(DummyEventListener::class);
        $eventListener->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($event));

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->registerListener(DummyEvent::class, [$eventListener, 'handle']);
        $eventDispatcher->registerListener(DummyEvent::class, [$eventListener, 'handle']);
        $eventDispatcher->dispatch($event);
    }

    public function testEventIsHandledByMultipleListeners()
    {
        $event = new DummyEvent();

        $eventListener = $this->createMock(DummyEventListener::class);
        $eventListener->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($event));
        $eventListener2 = $this->createMock(DummyEventListener2::class);
        $eventListener2->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($event));

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->registerListener(DummyEvent::class, [$eventListener, 'handle']);
        $eventDispatcher->registerListener(DummyEvent::class, [$eventListener2, 'handle']);
        $eventDispatcher->dispatch($event);
    }
}
