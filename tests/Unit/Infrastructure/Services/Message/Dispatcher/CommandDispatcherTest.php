<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Services\Message\Dispatcher;

use InSided\GetOnBoard\Infrastructure\Services\Message\Dispatcher\CommandDispatcher;
use PHPUnit\Framework\TestCase;

class CommandDispatcherTest extends TestCase
{
    public function testCommandHandlerGetsCalled()
    {
        $command = new DummyCommand();

        $commandHandler = $this->createMock(DummyCommandHandler::class);
        $commandHandler->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($command));

        $commandDispatcher = new CommandDispatcher();
        $commandDispatcher->registerHandler(DummyCommand::class, [$commandHandler, 'handle']);
        $commandDispatcher->dispatch($command);
    }

    public function testCommandHandlerDoesNotGetCalledWithTheWrongCommand()
    {
        $command = new DummyCommand();

        $commandHandler = $this->createMock(DummyCommandHandler::class);
        $commandHandler->expects($this->exactly(0))
            ->method('handle');

        $commandDispatcher = new CommandDispatcher();
        $commandDispatcher->registerHandler(SecondDummyCommand::class, [$commandHandler, 'handle']);
        $commandDispatcher->dispatch($command);
    }

    public function testCommandHandlerDoesNotGetOverridden()
    {
        $command = new DummyCommand();

        $commandHandler = $this->createMock(DummyCommandHandler::class);
        $commandHandler->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($command));
        $commandHandler->expects($this->exactly(0))
            ->method('anotherHandle');

        $commandDispatcher = new CommandDispatcher();
        $commandDispatcher->registerHandler(DummyCommand::class, [$commandHandler, 'handle']);
        $commandDispatcher->registerHandler(DummyCommand::class, [$commandHandler, 'anotherHandle']);
        $commandDispatcher->dispatch($command);
    }
}
