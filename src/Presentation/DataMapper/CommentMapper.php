<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Presentation\DataMapper;

use InSided\GetOnBoard\Presentation\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Comment as CoreComment;

class CommentMapper
{
    public static function map(CoreComment $coreComment): Comment
    {
        return new Comment(
            $coreComment->getId(),
            $coreComment->getText(),
        );
    }
}
