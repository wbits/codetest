<?php

namespace InSided\GetOnBoard\Core\Entity;

use InSided\GetOnBoard\Entity\Comment;

class User
{
    private string $id;
    private string $username;

    /**
     * @var Post[]
     */
    private array $posts = [];

    /**
     * @var string[]
     */
    private array $roles = [];

    /**
     * @var Comment[]
     */
    private array $comments = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    public function addPost($post): void
    {
        $this->posts[] = $post;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }
}
