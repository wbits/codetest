<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Services\Message\Dispatcher;

interface CommandDispatcherInterface
{
    public function dispatch(object $command): void;
}
