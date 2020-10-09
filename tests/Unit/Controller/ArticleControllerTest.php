<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\ArticleController;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Entity\Comment;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\Post;
use InSided\GetOnBoard\Entity\User;
use PHPUnit\Framework\TestCase;

class ArticleControllerTest extends TestCase
{
    private CommunityRepositoryInterface $communityRepository;
    private UserRepositoryInterface $userRepository;
    private ArticleController $controller;

    public function setUp(): void
    {
        $this->communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->controller = new ArticleController($this->communityRepository, $this->userRepository);
    }

    public function testUserCanListCommunityPosts(): void
    {
        $communityId = 'xyz';
        $posts = [
            $this->createMock(Post::class),
        ];
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('getPosts')
            ->willReturn($posts);
        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $actualPosts = $this->controller->listAction($communityId);
        $this->assertSame($posts, $actualPosts);
    }

    public function testUserGetsAnEmptyPostsListForNonExistingCommunity(): void
    {
        $communityId = 'xyz';
        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn(null);

        $actualPosts = $this->controller->listAction($communityId);
        $this->assertEmpty($actualPosts);
    }

    public function testUserCanCreateAnArticle()
    {
        $userId = 'abc';
        $communityId = 'xyz';
        $title = 'My awesome article';
        $content = 'My awesome content';

        $post = $this->createMock(Post::class);
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($title), $this->equalTo($content), $this->equalTo('article'))
            ->willReturn($post);

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('addPost')
            ->with($this->equalTo($post));

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $createdPost = $this->controller->createAction($userId, $communityId, $title, $content);

        $this->assertSame($post, $createdPost);
    }

    public function testUserCanUpdateAnArticle()
    {
        $userId = 'abc';
        $articleId = 'def';
        $communityId = 'xyz';
        $title = 'My awesome article';
        $content = 'My awesome content';

        $article = $this->createMock(Post::class);
        $article->expects($this->once())
            ->method('getId')
            ->willReturn($articleId);
        $posts = [
            $article,
        ];
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getPosts')
            ->willReturn($posts);

        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('updatePost')
            ->with($this->equalTo($articleId), $this->equalTo($title), $this->equalTo($content))
            ->willReturn($article);

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $updatedArticle = $this->controller->updateAction($userId, $communityId, $articleId, $title, $content);

        $this->assertSame($article, $updatedArticle);
    }

    public function testUserCanComment()
    {
        $articleId = 'abc';
        $userId = 'def';
        $communityId = 'xyz';
        $content = 'This is amazing';

        $comment = $this->createMock(Comment::class);
        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('addComment')
            ->with($this->equalTo($articleId), $this->equalTo($content))
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

        $createdComment = $this->controller->commentAction($userId, $communityId, $articleId, $content);

        $this->assertSame($comment, $createdComment);
    }

    public function testCommentsCanBeDisabled()
    {
        $articleId = 'abc';
        $communityId = 'xyz';

        $community = $this->createMock(Community::class);
        $community->expects($this->once())
            ->method('disableCommentsForArticle')
            ->with($this->equalTo($articleId));

        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $this->controller->disableCommentsAction($communityId, $articleId);
    }
}
