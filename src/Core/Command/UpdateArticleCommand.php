<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Command;

use InSided\GetOnBoard\Core\Entity\Post;

class UpdateArticleCommand
{
    private Post $article;
    private string $title;
    private string $text;

    public function __construct(Post $article, string $title, string $text)
    {
        $this->article = $article;
        $this->title = $title;
        $this->text = $text;
    }

    public function getArticle(): Post
    {
        return $this->article;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
