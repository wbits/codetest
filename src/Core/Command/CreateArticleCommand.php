<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Command;

class CreateArticleCommand
{
    private string $articleId;
    private string $communityId;
    private string $userId;
    private string $title;
    private string $text;

    public function __construct(
        string $articleId,
        string $communityId,
        string $userId,
        string $title,
        string $text
    ) {

        $this->articleId = $articleId;
        $this->communityId = $communityId;
        $this->userId = $userId;
        $this->title = $title;
        $this->text = $text;
    }

    public function getArticleId(): string
    {
        return $this->articleId;
    }

    public function getCommunityId(): string
    {
        return $this->communityId;
    }

    public function getUserId(): string
    {
        return $this->userId;
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
