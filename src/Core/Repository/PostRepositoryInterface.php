<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Repository;

use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;

interface PostRepositoryInterface
{
    public function getPost(string $id): ?Post;

    /**
     * @return Post[]
     */
    public function getPostsByCommunity(Community $community): array;

    /**
     * @return Post[]
     */
    public function getArticlesByCommunity(Community $community): array;

    /**
     * @return Post[]
     */
    public function getConversationsByCommunity(Community $community): array;

    /**
     * @return Post[]
     */
    public function getQuestionsByCommunity(Community $community): array;

    public function addPost(Post $post): void;
}
