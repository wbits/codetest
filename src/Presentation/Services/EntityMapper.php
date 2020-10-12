<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Presentation\Services;

use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Presentation\DataMapper\CommentMapper;
use InSided\GetOnBoard\Presentation\DataMapper\PostMapper;

/**
 * Converts core entities to plain objects for presentation purposes
 */
class EntityMapper
{
    public function map($entity)
    {
        switch (get_class($entity)) {
            case Post::class:
                return PostMapper::map($entity);
            case Comment::class:
                return CommentMapper::map($entity);
            default:
                // log?
                return null;
        }
    }
}
