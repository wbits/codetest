<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Repository\CommentRepositoryInterface;

class InMemoryCommentRepository implements CommentRepositoryInterface
{
    /**
     * @var Comment[]
     */
    private array $comments = [];

    /**
     * @return Comment[]
     */
    public function getCommentsForPost(Post $post): array
    {
        return array_filter(
            $this->comments,
            fn(Comment $comment) => $comment->getParent()->getId() == $post->getId()
        );
    }

    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }
}
