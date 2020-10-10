<?php

namespace InSided\GetOnBoard\Core\Entity;

use InSided\GetOnBoard\Core\Exception\Post\InvalidPostTypeException;

class Post
{
    public const TYPE_ARTICLE = 'article';
    public const TYPE_CONVERSATION = 'conversation';
    public const TYPE_QUESTION = 'question';

    private string $id;
    private string $type;
    private Community $community;
    private User $user;
    private string $text = '';
    private string $title = '';
    private bool $deleted = false;
    private bool $commentsAllowed = true;

    /**
     * @var Comment[]
     */
    private array $comments = [];

    public function __construct(string $id, string $type)
    {
        if (!in_array($type, [self::TYPE_ARTICLE, self::TYPE_CONVERSATION, self::TYPE_QUESTION])) {
            throw new InvalidPostTypeException($type);
        }

        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCommunity(): Community
    {
        return $this->community;
    }

    public function setCommunity(Community $community): void
    {
        $this->community = $community;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted($deleted): void
    {
        $this->deleted = $deleted;
    }

    public function isCommentsAllowed(): bool
    {
        return $this->commentsAllowed;
    }

    public function setCommentsAllowed($commentsAllowed)
    {
        $this->commentsAllowed = $commentsAllowed;
    }

    public function isArticle(): bool
    {
        return $this->type === self::TYPE_ARTICLE;
    }

    public function isConversation(): bool
    {
        return $this->type === self::TYPE_CONVERSATION;
    }

    public function isQuestion(): bool
    {
        return $this->type === self::TYPE_QUESTION;
    }
}
