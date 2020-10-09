<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Services\Message\Dispatcher;

use InSided\GetOnBoard\Core\Services\Message\Dispatcher\CommandDispatcherInterface;

class CommandDispatcher implements CommandDispatcherInterface
{
    private array $commandHandlers = [];

    public function registerHandler(string $commandName, callable $commandHandler): void
    {
        if (array_key_exists($commandName, $this->commandHandlers)) {
            // only one handler per command. Log / throw / override?
            return;
        }

        // TODO: check if handler can handle the command before registering it
        $this->commandHandlers[$commandName] = $commandHandler;
    }

    public function dispatch(object $command): void
    {
        $commandName = get_class($command);
        if (!array_key_exists($commandName, $this->commandHandlers)) {
            // perhaps log or throw unhandled command
            return;
        }

        call_user_func($this->commandHandlers[$commandName], $command);
    }
}
