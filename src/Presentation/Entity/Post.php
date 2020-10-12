<?php

namespace InSided\GetOnBoard\Presentation\Entity;

class Post
{
    private string $id;
    private string $title;
    private string $text;
    private string $type;
    /**
     * @var Comment[]
     */
    private array $comments;
    private bool $deleted;
    private bool $commentsAllowed;

    public function __construct(
        string $id,
        string $title,
        string $text,
        string $type,
        array $comments,
        bool $deleted,
        bool $commentsAllowed
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->type = $type;
        $this->comments = $comments;
        $this->deleted = $deleted;
        $this->commentsAllowed = $commentsAllowed;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParent()
    {
        return null;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function isCommentsAllowed(): bool
    {
        return $this->commentsAllowed;
    }
}
