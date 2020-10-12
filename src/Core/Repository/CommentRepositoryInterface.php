<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Repository;

use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Post;

interface CommentRepositoryInterface
{
    /**
     * @return Comment[]
     */
    public function getCommentsForPost(Post $post): array;

    public function addComment(Comment $comment): void;
}
