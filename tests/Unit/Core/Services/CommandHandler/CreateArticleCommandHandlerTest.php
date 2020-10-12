<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Core\Services\CommandHandler;

use InSided\GetOnBoard\Core\Command\CreateArticleCommand;
use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Entity\User;
use InSided\GetOnBoard\Core\Event\ArticleCreatedEvent;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\PostRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Core\Services\CommandHandler\CreateArticleCommandHandler;
use InSided\GetOnBoard\Core\Services\Message\Dispatcher\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

class CreateArticleCommandHandlerTest extends TestCase
{
    public function testArticleIsCreated()
    {
        $userId = 'u';
        $communityId = 'c';
        $articleId = 'a';

        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $postRepository->expects($this->once())
            ->method('addPost')
            ->with($this->isInstanceOf(Post::class));

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($this->createMock(User::class));

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($this->createMock(Community::class));

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ArticleCreatedEvent::class));

        $createCommand = new CreateArticleCommand(
            $articleId,
            $communityId,
            $userId,
            'title',
            'text'
        );

        $handler = new CreateArticleCommandHandler(
            $postRepository,
            $communityRepository,
            $userRepository,
            $eventDispatcher
        );
        $handler->handle($createCommand);
    }
}
