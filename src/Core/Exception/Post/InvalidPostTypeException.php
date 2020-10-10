<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Exception\Post;

use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Exception\InSidedException;

class InvalidPostTypeException extends InSidedException
{
    public function __construct(string $type)
    {
        $message = sprintf(
            'Invalid "%s" post type. Valid types are: "%s", "%s", "%s"',
            $type,
            Post::TYPE_ARTICLE,
            Post::TYPE_CONVERSATION,
            Post::TYPE_QUESTION
        );
        parent::__construct($message);
    }
}
