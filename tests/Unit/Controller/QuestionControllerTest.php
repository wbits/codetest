<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\QuestionController;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Entity\Comment;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\Post;
use InSided\GetOnBoard\Entity\User;
use PHPUnit\Framework\TestCase;

class QuestionControllerTest extends TestCase
{
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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $controller = new QuestionController($communityRepository);
        $actualQuestions = $controller->listAction($communityId);

        $this->assertSame($questions, $actualQuestions);
    }

    public function testUserGetsAnEmptyQuestionListForNonExistingCommunity(): void
    {
        $communityId = 'xyz';
        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn(null);
        $controller = new QuestionController($communityRepository);

        $actualQuestions = $controller->listAction($communityId);
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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new QuestionController($communityRepository);
        $createdQuestion = $controller->createAction($userId, $communityId, $title, $content);

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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new QuestionController($communityRepository);
        $updatedQuestion = $controller->updateAction($userId, $communityId, $questionId, $title, $content);

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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new QuestionController($communityRepository);
        $controller->deleteAction($userId, $communityId, $questionId);
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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new QuestionController($communityRepository);
        $createdComment = $controller->commentAction($userId, $communityId, $questionId, $content);

        $this->assertSame($comment, $createdComment);
    }
}
