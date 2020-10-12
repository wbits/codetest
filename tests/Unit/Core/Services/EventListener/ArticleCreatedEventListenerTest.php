<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Core\Services\EventListener;

use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Entity\User;
use InSided\GetOnBoard\Core\Event\ArticleCreatedEvent;
use InSided\GetOnBoard\Core\Services\EventListener\ArticleCreatedEventListener;
use PHPUnit\Framework\TestCase;

class ArticleCreatedEventListenerTest extends TestCase
{
    public function testArticleGetsAddedToCommunityAndUser()
    {
        $community = $this->createMock(Community::class);
        $user = $this->createMock(User::class);
        $article = $this->createMock(Post::class);
        $community->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($article));
        $user->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($article));
        $article->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        $article->expects($this->once())
            ->method('getCommunity')
            ->willReturn($community);

        $listener = new ArticleCreatedEventListener();
        $listener->handle(new ArticleCreatedEvent($article));
    }
}
