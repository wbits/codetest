<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Controller;

use InSided\GetOnBoard\Controller\ArticleController;
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

class ArticleControllerTest extends TestCase
{
    private CommunityRepositoryInterface $communityRepository;
    private UserRepositoryInterface $userRepository;
    private PostRepositoryInterface $postRepository;
    private CommentRepositoryInterface $commentRepository;
    private IdGeneratorInterface $idGenerator;
    private EntityMapper $entityMapper;
    private ArticleController $controller;

    public function setUp(): void
    {
        $this->communityRepository = $this->createMock(CommunityRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->postRepository = $this->createMock(PostRepositoryInterface::class);
        $this->commentRepository = $this->createMock(CommentRepositoryInterface::class);
        $this->idGenerator = $this->createMock(IdGeneratorInterface::class);
        $this->entityMapper = $this->createMock(EntityMapper::class);
        $this->controller = new ArticleController(
            $this->communityRepository,
            $this->userRepository,
            $this->postRepository,
            $this->commentRepository,
            $this->entityMapper,
            $this->idGenerator
        );
    }

    public function testUserCanListCommunityPosts(): void
    {
        $communityId = 'xyz';
        $posts = [
            $this->createMock(Post::class),
        ];
        $community = $this->createMock(Community::class);
        $this->communityRepository->expects($this->once())
            ->method('getCommunity')
            ->with($this->equalTo($communityId))
            ->willReturn($community);

        $this->postRepository->expects($this->once())
            ->method('getArticlesByCommunity')
            ->with($this->equalTo($community))
            ->willReturn($posts);

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->equalTo($posts[0]))
            ->willReturn($this->createMock(PresentationPost::class));

        $actualPosts = $this->controller->listAction($communityId);
        $this->assertIsArray($actualPosts);
        $this->assertCount(1, $actualPosts);
        $this->assertInstanceOf(PresentationPost::class, $actualPosts[0]);
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

    public function testUserCanUpdateAnArticle()
    {
        $userId = 'abc';
        $articleId = 'def';
        $communityId = 'xyz';
        $title = 'My awesome article';
        $content = 'My awesome content';

        $article = $this->createMock(Post::class);
        $article->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo($title));
        $article->expects($this->once())
            ->method('setText')
            ->with($this->equalTo($content));

        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($articleId))
            ->willReturn($article);

        $this->entityMapper->expects($this->once())
            ->method('map')
            ->with($this->isInstanceOf(Post::class))
            ->willReturn($this->createMock(PresentationPost::class));

        $updatedArticle = $this->controller->updateAction($userId, $communityId, $articleId, $title, $content);

        $this->assertInstanceOf(PresentationPost::class, $updatedArticle);
    }

    public function testUserCanComment()
    {
        $articleId = 'abc';
        $userId = 'def';
        $communityId = 'xyz';
        $content = 'This is amazing';

        $article = $this->createMock(Post::class);
        $article->expects($this->once())
            ->method('addComment')
            ->with($this->isInstanceOf(Comment::class));

        $this->idGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('randomId');

        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($articleId))
            ->willReturn($article);

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

        $createdComment = $this->controller->commentAction($userId, $communityId, $articleId, $content);

        $this->assertInstanceOf(PresentationComment::class, $createdComment);
    }

    public function testCommentsCanBeDisabled()
    {
        $articleId = 'abc';
        $communityId = 'xyz';

        $article = $this->createMock(Post::class);
        $article->expects($this->once())
            ->method('setCommentsAllowed')
            ->with($this->equalTo(false));
        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($articleId))
            ->willReturn($article);

        $this->controller->disableCommentsAction($communityId, $articleId);
    }

    public function testArticleCanBeDeleted()
    {
        $userId = 'id';
        $articleId = 'abc';
        $communityId = 'xyz';

        $article = $this->createMock(Post::class);
        $article->expects($this->once())
            ->method('setDeleted')
            ->with($this->equalTo(true));
        $this->postRepository->expects($this->once())
            ->method('getPost')
            ->with($this->equalTo($articleId))
            ->willReturn($article);

        $this->controller->deleteAction($userId, $communityId, $articleId);
    }
}
