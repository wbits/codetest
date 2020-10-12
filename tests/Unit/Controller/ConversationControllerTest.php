<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\ConversationController;
use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Entity\User;
use InSided\GetOnBoard\Core\Repository\CommentRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\PostRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Core\Services\IdGeneratorInterface;
use InSided\GetOnBoard\Presentation\Entity\Comment as PresentationComment;
use InSided\GetOnBoard\Presentation\Entity\Post as PresentationPost;
use InSided\GetOnBoard\Presentation\Services\EntityMapper;
use PHPUnit\Framework\TestCase;

class ConversationControllerTest extends TestCase
{
    private CommunityRepositoryInterface $communityRepository;
    private UserRepositoryInterface $userRepository;
    private PostRepositoryInterface $postRepository;
    private CommentRepositoryInterface $commentRepository;
    private IdGeneratorInterface $idGenerator;
    private EntityMapper $entityMapper;
    private ConversationController $controller;

    public function setUp(): void
    {
        $this->communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->postRepository = $this->createMock(PostRepositoryInterface::class);
        $this->commentRepository = $this->createMock(CommentRepositoryInterface::class);
        $this->idGenerator = $this->createMock(IdGeneratorInterface::class);
        $this->entityMapper = $this->createMock(EntityMapper::class);
        $this->controller = new ConversationController(
            $this->communityRepository,
            $this->userRepository,
            $this->postRepository,
            $this->commentRepository,
            $this->entityMapper,
            $this->idGenerator
        );
    }

    public function testUserCanListConversations()
    {
        $communityId = 'xyz';

        $conversations = [
            $this->createMock(Post::class),
        ];
        $community = $this->createMock(Community::class);
        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $this->postRepository->expects($this->once())
            ->method('getConversationsByCommunity')
            ->with($this->equalTo($community))
            ->willReturn($conversations);

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->equalTo($conversations[0]))
            ->willReturn($this->createMock(PresentationPost::class));

        $actualConversations = $this->controller->listAction($communityId);
        $this->assertIsArray($actualConversations);
        $this->assertCount(1, $actualConversations);
        $this->assertInstanceOf(PresentationPost::class, $actualConversations[0]);
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

        $this->idGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('randomId');

        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('addPost')
            ->with($this->isInstanceOf(Post::class));

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('addPost')
            ->with($this->isInstanceOf(Post::class));

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $this->postRepository->expects($this->once())
            ->method('addPost')
            ->with($this->isInstanceOf(Post::class));

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->isInstanceOf(Post::class))
            ->willReturn($this->createMock(PresentationPost::class));

        $createdPost = $this->controller->createAction($userId, $communityId, $title, $content);

        $this->assertInstanceOf(PresentationPost::class, $createdPost);
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
            ->method('setTitle')
            ->with($this->equalTo($title));
        $conversation->expects($this->once())
            ->method('setText')
            ->with($this->equalTo($content));

        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($conversationId))
            ->willReturn($conversation);

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->isInstanceOf(Post::class))
            ->willReturn($this->createMock(PresentationPost::class));

        $updatedConversation = $this->controller->updateAction($userId, $communityId, $conversationId, $title, $content);

        $this->assertInstanceOf(PresentationPost::class, $updatedConversation);
    }

    public function testConversationCanBeDeleted()
    {
        $userId = 'mno';
        $conversationId = 'abc';
        $communityId = 'xyz';

        $conversation = $this->createMock(Post::class);
        $conversation->expects($this->once())
            ->method('setDeleted')
            ->with($this->equalTo(true));
        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($conversationId))
            ->willReturn($conversation);

        $this->controller->deleteAction($userId, $communityId, $conversationId);
    }

    public function testUserCanComment()
    {
        $conversationId = 'abc';
        $userId = 'def';
        $communityId = 'xyz';
        $content = 'This is amazing';

        $comment = $this->createMock(Post::class);
        $comment->expects($this->once())
            ->method('addComment')
            ->with($this->isInstanceOf(Comment::class));

        $this->idGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('randomId');

        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($conversationId))
            ->willReturn($comment);

        $user = $this->createMock(User::class);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $this->commentRepository->expects($this->once())
            ->method('addComment')
            ->with($this->isInstanceOf(Comment::class));

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->isInstanceOf(Comment::class))
            ->willReturn($this->createMock(PresentationComment::class));

        $createdComment = $this->controller->commentAction($userId, $communityId, $conversationId, $content);

        $this->assertInstanceOf(PresentationComment::class, $createdComment);
    }
}
