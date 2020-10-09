<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Services\Message\Dispatcher;

class DummyEventListener
{
    public function handle(DummyEvent $event)
    {
        // do nothing
    }
}
