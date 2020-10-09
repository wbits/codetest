<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\ConversationController;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Entity\Comment;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\Post;
use InSided\GetOnBoard\Entity\User;
use PHPUnit\Framework\TestCase;

class ConversationControllerTest extends TestCase
{
    public function testUserCanListConversations()
    {
        $communityId = 'xyz';

        $conversations = [
            $this->createMock(Post::class),
        ];
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('getPosts')
            ->willReturn($conversations);

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $controller = new ConversationController($communityRepository);
        $actualConversations = $controller->listAction($communityId);

        $this->assertSame($conversations, $actualConversations);
    }

    public function testUserGetsAnEmptyConversationListForNonExistingCommunity(): void
    {
        $communityId = 'xyz';
        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn(null);
        $controller = new ConversationController($communityRepository);

        $actualConversations = $controller->listAction($communityId);
        $this->assertEmpty($actualConversations);
    }

    public function testUserCanCreateAnArticle()
    {
        $userId = 'abc';
        $communityId = 'xyz';
        $title = 'My awesome article';
        $content = 'My awesome content';

        $conversation = $this->createMock(Post::class);
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($title), $this->equalTo($content), $this->equalTo('conversation'))
            ->willReturn($conversation);

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($conversation));

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new ConversationController($communityRepository);
        $createdConversation = $controller->createAction($userId, $communityId, $title, $content);

        $this->assertSame($conversation, $createdConversation);
    }

    public function testUserCanUpdateAConversation()
    {
        $userId = 'abc';
        $conversationId = 'def';
        $communityId = 'xyz';
        $title = 'Howdy!';
        $content = 'What do we eat for lunch?';

        $conversation = $this->createMock(Post::class);
        $conversation->expects($this->once())
            ->method('getId')
            ->willReturn($conversationId);
        $posts = [
            $conversation,
        ];
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getPosts')
            ->willReturn($posts);

        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('updatePost')
            ->with($this->equalTo($conversationId), $this->equalTo($title), $this->equalTo($content))
            ->willReturn($conversation);

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new ConversationController($communityRepository);
        $updatedConversation = $controller->updateAction($userId, $communityId, $conversationId, $title, $content);

        $this->assertSame($conversation, $updatedConversation);
    }

    public function testConversationCanBeDeleted()
    {
        $userId = 'mno';
        $conversationId = 'abc';
        $communityId = 'xyz';

        $conversation = $this->createMock(Post::class);
        $conversation->expects($this->once())
            ->method('getId')
            ->willReturn($conversationId);
        $posts = [
            $conversation,
        ];
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getPosts')
            ->willReturn($posts);

        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('deletePost');

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new ConversationController($communityRepository);
        $controller->deleteAction($userId, $communityId, $conversationId);
    }

    public function testUserCanComment()
    {
        $conversationId = 'abc';
        $userId = 'def';
        $communityId = 'xyz';
        $content = 'This is amazing';

        $comment = $this->createMock(Comment::class);
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('addComment')
            ->with($this->equalTo($conversationId), $this->equalTo($content))
            ->willReturn($comment);
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('addComment')
            ->with($this->equalTo($comment));

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new ConversationController($communityRepository);
        $createdComment = $controller->commentAction($userId, $communityId, $conversationId, $content);

        $this->assertSame($comment, $createdComment);
    }
}
