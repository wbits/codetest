<?php

namespace InSided\GetOnBoard\Core\Entity;

class Comment
{
    private string $id;
    private string $text = '';
    private Post $parent;
    private User $user;

    public function __construct(string $id)
    {
        $this->id =  $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setParent(Post $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): Post
    {
        return $this->parent;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
