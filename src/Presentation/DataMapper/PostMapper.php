<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Presentation\DataMapper;

use InSided\GetOnBoard\Entity\Post;
use InSided\GetOnBoard\Core\Entity\Post as CorePost;

class PostMapper
{
    public static function map(CorePost $corePost): Post
    {
        return new Post(
            $corePost->getId(),
            $corePost->getTitle(),
            $corePost->getText(),
            $corePost->getType(),
            array_map(
                [CommentMapper::class, 'map'],
                $corePost->getComments()
            ),
            $corePost->isDeleted(),
            $corePost->isCommentsAllowed()
        );
    }
}
