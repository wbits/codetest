<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Services\Message\Dispatcher;

class DummyCommandHandler
{
    public function handle(DummyCommand $command)
    {
        // do nothing
    }

    public function anotherHandle(DummyCommand $command)
    {
        // do nothing
    }
}
