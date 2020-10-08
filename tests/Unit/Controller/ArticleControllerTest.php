<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\ArticleController;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Entity\Comment;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\Post;
use InSided\GetOnBoard\Entity\User;
use PHPUnit\Framework\TestCase;

class ArticleControllerTest extends TestCase
{
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
        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $controller = new ArticleController($communityRepository);

        $actualPosts = $controller->listAction($communityId);
        $this->assertSame($posts, $actualPosts);
    }

    public function testUserGetsAnEmptyPostsListForNonExistingCommunity(): void
    {
        $communityId = 'xyz';
        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn(null);
        $controller = new ArticleController($communityRepository);

        $actualPosts = $controller->listAction($communityId);
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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new ArticleController($communityRepository);
        $createdPost = $controller->createAction($userId, $communityId, $title, $content);

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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new ArticleController($communityRepository);
        $updatedArticle = $controller->updateAction($userId, $communityId, $articleId, $title, $content);

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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);
        $communityRepository->expects($this->once())
            ->method('getUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $controller = new ArticleController($communityRepository);
        $createdComment = $controller->commentAction($userId, $communityId, $articleId, $content);

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

        $communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $controller = new ArticleController($communityRepository);
        $controller->disableCommentsAction($communityId, $articleId);
    }
}
