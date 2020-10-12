<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Event;

use InSided\GetOnBoard\Core\Entity\Post;

class ArticleUpdatedEvent
{
    private Post $article;

    public function __construct(Post $article)
    {
        $this->article = $article;
    }

    public function getArticle(): Post
    {
        return $this->article;
    }
}
