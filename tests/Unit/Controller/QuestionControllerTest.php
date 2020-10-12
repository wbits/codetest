<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\QuestionController;
use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Entity\User;
use InSided\GetOnBoard\Core\Repository\CommentRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\PostRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Core\Services\IdGeneratorInterface;
use InSided\GetOnBoard\Entity\Comment as PresentationComment;
use InSided\GetOnBoard\Entity\Post as PresentationPost;
use InSided\GetOnBoard\Presentation\Services\EntityMapper;
use PHPUnit\Framework\TestCase;

class QuestionControllerTest extends TestCase
{
    private CommunityRepositoryInterface $communityRepository;
    private UserRepositoryInterface $userRepository;
    private PostRepositoryInterface $postRepository;
    private CommentRepositoryInterface $commentRepository;
    private IdGeneratorInterface $idGenerator;
    private EntityMapper $entityMapper;
    private QuestionController $controller;

    public function setUp(): void
    {
        $this->communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->postRepository = $this->createMock(PostRepositoryInterface::class);
        $this->commentRepository = $this->createMock(CommentRepositoryInterface::class);
        $this->idGenerator = $this->createMock(IdGeneratorInterface::class);
        $this->entityMapper = $this->createMock(EntityMapper::class);
        $this->controller = new QuestionController(
            $this->communityRepository,
            $this->userRepository,
            $this->postRepository,
            $this->commentRepository,
            $this->entityMapper,
            $this->idGenerator
        );
    }

    public function testUserCanListQuestions()
    {
        $communityId = 'xyz';

        $questions = [
            $this->createMock(Post::class),
        ];
        $community = $this->createMock(Community::class);
        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $this->postRepository->expects($this->once())
            ->method('getQuestionsByCommunity')
            ->with($this->equalTo($community))
            ->willReturn($questions);

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->equalTo($questions[0]))
            ->willReturn($this->createMock(PresentationPost::class));

        $actualQuestions = $this->controller->listAction($communityId);
        $this->assertIsArray($actualQuestions);
        $this->assertCount(1, $actualQuestions);
        $this->assertInstanceOf(PresentationPost::class, $actualQuestions[0]);
    }

    public function testUserGetsAnEmptyQuestionListForNonExistingCommunity(): void
    {
        $communityId = 'xyz';
        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn(null);

        $actualQuestions = $this->controller->listAction($communityId);
        $this->assertEmpty($actualQuestions);
    }

    public function testUserCanAskAQuestion()
    {
        $userId = 'abc';
        $communityId = 'xyz';
        $title = 'Area of a square';
        $content = 'I need the formula for calculating the area of a square, can someone help me, please?';

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

    public function testUserCanUpdateAQuestion()
    {
        $userId = 'abc';
        $questionId = 'def';
        $communityId = 'xyz';
        $title = 'Earth - Sun distance';
        $content = 'What is the distance from Earth to Sun?';

        $question = $this->createMock(Post::class);
        $question->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo($title));
        $question->expects($this->once())
            ->method('setText')
            ->with($this->equalTo($content));

        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($questionId))
            ->willReturn($question);

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->isInstanceOf(Post::class))
            ->willReturn($this->createMock(PresentationPost::class));

        $updatedQuestion = $this->controller->updateAction($userId, $communityId, $questionId, $title, $content);

        $this->assertInstanceOf(PresentationPost::class, $updatedQuestion);
    }

    public function testQuestionCanBeDeleted()
    {
        $userId = 'mno';
        $questionId = 'abc';
        $communityId = 'xyz';

        $question = $this->createMock(Post::class);
        $question->expects($this->once())
            ->method('setDeleted')
            ->with($this->equalTo(true));
        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($questionId))
            ->willReturn($question);

        $this->controller->deleteAction($userId, $communityId, $questionId);
    }

    public function testUserCanComment()
    {
        $questionId = 'abc';
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
            ->with($this->equalTo($questionId))
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

        $createdComment = $this->controller->commentAction($userId, $communityId, $questionId, $content);

        $this->assertInstanceOf(PresentationComment::class, $createdComment);
    }
}
