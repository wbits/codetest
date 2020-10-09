<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\ConversationController;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Entity\Comment;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\Post;
use InSided\GetOnBoard\Entity\User;
use PHPUnit\Framework\TestCase;

class ConversationControllerTest extends TestCase
{
    private CommunityRepositoryInterface $communityRepository;
    private UserRepositoryInterface $userRepository;
    private ConversationController $controller;

    public function setUp(): void
    {
        $this->communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->controller = new ConversationController($this->communityRepository, $this->userRepository);
    }

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

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $actualConversations = $this->controller->listAction($communityId);

        $this->assertSame($conversations, $actualConversations);
    }

    public function testUserGetsAnEmptyConversationListForNonExistingCommunity(): void
    {
        $communityId = 'xyz';
        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn(null);

        $actualConversations = $this->controller->listAction($communityId);
        $this->assertEmpty($actualConversations);
    }

    public function testUserCanCreateAConversation()
    {
        $userId = 'abc';
        $communityId = 'xyz';
        $title = 'My awesome conversation';
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

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $createdConversation = $this->controller->createAction($userId, $communityId, $title, $content);

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

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $updatedConversation = $this->controller->updateAction($userId, $communityId, $conversationId, $title, $content);

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

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $this->controller->deleteAction($userId, $communityId, $conversationId);
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

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $createdComment = $this->controller->commentAction($userId, $communityId, $conversationId, $content);

        $this->assertSame($comment, $createdComment);
    }
}
