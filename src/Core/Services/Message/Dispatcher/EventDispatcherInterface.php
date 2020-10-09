<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Services\Message\Dispatcher;

interface EventDispatcherInterface
{
    public function dispatch(object $event): void;
}
