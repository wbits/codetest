<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\QuestionController;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Entity\Comment;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\Post;
use InSided\GetOnBoard\Entity\User;
use PHPUnit\Framework\TestCase;

class QuestionControllerTest extends TestCase
{
    private CommunityRepositoryInterface $communityRepository;
    private UserRepositoryInterface $userRepository;
    private QuestionController $controller;

    public function setUp(): void
    {
        $this->communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->controller = new QuestionController($this->communityRepository, $this->userRepository);
    }

    public function testUserCanListQuestions()
    {
        $communityId = 'xyz';

        $questions = [
            $this->createMock(Post::class),
        ];
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('getPosts')
            ->willReturn($questions);

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $actualQuestions = $this->controller->listAction($communityId);

        $this->assertSame($questions, $actualQuestions);
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

        $question = $this->createMock(Post::class);
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($title), $this->equalTo($content), $this->equalTo('question'))
            ->willReturn($question);

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($question));

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $createdQuestion = $this->controller->createAction($userId, $communityId, $title, $content);

        $this->assertSame($question, $createdQuestion);
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
            ->method('getId')
            ->willReturn($questionId);
        $posts = [
            $question,
        ];
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getPosts')
            ->willReturn($posts);

        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('updatePost')
            ->with($this->equalTo($questionId), $this->equalTo($title), $this->equalTo($content))
            ->willReturn($question);

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $updatedQuestion = $this->controller->updateAction($userId, $communityId, $questionId, $title, $content);

        $this->assertSame($question, $updatedQuestion);
    }

    public function testQuestionCanBeDeleted()
    {
        $userId = 'mno';
        $questionId = 'abc';
        $communityId = 'xyz';

        $question = $this->createMock(Post::class);
        $question->expects($this->once())
            ->method('getId')
            ->willReturn($questionId);
        $posts = [
            $question,
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

        $this->controller->deleteAction($userId, $communityId, $questionId);
    }

    public function testUserCanComment()
    {
        $questionId = 'abc';
        $userId = 'def';
        $communityId = 'xyz';
        $content = 'This is amazing';

        $comment = $this->createMock(Comment::class);
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('addComment')
            ->with($this->equalTo($questionId), $this->equalTo($content))
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

        $createdComment = $this->controller->commentAction($userId, $communityId, $questionId, $content);

        $this->assertSame($comment, $createdComment);
    }
}
