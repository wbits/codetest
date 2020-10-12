<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Core\Services\CommandHandler;

use InSided\GetOnBoard\Core\Command\UpdateArticleCommand;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Event\ArticleUpdatedEvent;
use InSided\GetOnBoard\Core\Services\CommandHandler\UpdateArticleCommandHandler;
use InSided\GetOnBoard\Core\Services\Message\Dispatcher\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

class UpdateArticleCommandHandlerTest extends TestCase
{
    public function testArticleIsUpdated(): void
    {
        $newTitle = 'New title';
        $newText = 'New Text';

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ArticleUpdatedEvent::class));

        $article = $this->createMock(Post::class);
        $article->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo($newTitle));
        $article->expects($this->once())
            ->method('setText')
            ->with($this->equalTo($newText));

        $command = new UpdateArticleCommand($article, $newTitle, $newText);
        $handler = new UpdateArticleCommandHandler($eventDispatcher);
        $handler->handle($command);
    }
}
