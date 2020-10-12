<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Infrastructure\Repository\InMemoryPostRepository;
use PHPUnit\Framework\TestCase;

class InMemoryPostRepositoryTest extends TestCase
{
    private InMemoryPostRepository $postRepository;

    public function setUp(): void
    {
        $this->postRepository = new InMemoryPostRepository();
    }

    public function testNullGetsReturnedIfUserNotFound(): void
    {
        $storedUser = $this->postRepository->getPost(uniqid());

        $this->assertNull($storedUser);
    }

    public function testStoredPostCanBeRetrieved(): void
    {
        $post = new Post('id', Post::TYPE_QUESTION);
        $this->postRepository->addPost($post);
        $storedUser = $this->postRepository->getPost($post->getId());

        $this->assertSame($post, $storedUser);
    }

    public function testPostsCanBeFiltered(): void
    {
        $community = new Community('my-community');
        $posts = [
            new Post('id1', Post::TYPE_QUESTION),
            new Post('id2', Post::TYPE_CONVERSATION),
            new Post('id3', Post::TYPE_CONVERSATION),
            new Post('id4', Post::TYPE_ARTICLE),
            new Post('id5', Post::TYPE_ARTICLE),
            new Post('id6', Post::TYPE_ARTICLE),
        ];
        foreach ($posts as $post) {
            $post->setCommunity($community);
            $this->postRepository->addPost($post);
        }

        $this->assertCount(6, $this->postRepository->getPostsByCommunity($community));
        $this->assertCount(1, $this->postRepository->getQuestionsByCommunity($community));
        $this->assertCount(2, $this->postRepository->getConversationsByCommunity($community));
        $this->assertCount(3, $this->postRepository->getArticlesByCommunity($community));
    }
}
