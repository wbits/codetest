<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Repository\PostRepositoryInterface;

class InMemoryPostRepository implements PostRepositoryInterface
{
    /**
     * @var Post[]
     */
    private array $posts = [];

    public function getPost(string $id): ?Post
    {
        foreach ($this->posts as $post) {
            if ($post->getId() == $id) {
                return $post;
            }
        }

        return null;
    }

    /**
     * @return Post[]
     */
    public function getPostsByCommunity(Community $community): array
    {
        return array_filter(
            $this->posts,
            fn(Post $post) => $post->getCommunity()->getId() == $community->getId() && !$post->isDeleted()
        );
    }

    /**
     * @return Post[]
     */
    public function getArticlesByCommunity(Community $community): array
    {
        return array_filter(
            $this->posts,
            fn(Post $post) =>
                $post->getCommunity()->getId() == $community->getId() && !$post->isDeleted() && $post->isArticle()
        );
    }

    /**
     * @return Post[]
     */
    public function getConversationsByCommunity(Community $community): array
    {
        return array_filter(
            $this->posts,
            fn(Post $post) =>
                $post->getCommunity()->getId() == $community->getId() && !$post->isDeleted() && $post->isConversation()
        );
    }

    /**
     * @return Post[]
     */
    public function getQuestionsByCommunity(Community $community): array
    {
        return array_filter(
            $this->posts,
            fn(Post $post) =>
                $post->getCommunity()->getId() == $community->getId() && !$post->isDeleted() && $post->isQuestion()
        );
    }

    public function addPost(Post $post): void
    {
        $this->posts[] = $post;
    }
}
