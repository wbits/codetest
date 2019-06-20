<?php

namespace InSided\GetOnBoard\Entity;

class User
{
    public $id;
    public $username;
    public $posts;
    public $roles;
    public $comments;

    public function __construct()
    {
        $this->id = uniqid();
        $this->posts = [];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    /**
     * @param $post
     */
    public function addPost($post): void
    {
        $this->posts[] = $post;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function addComment($comment): void
    {
        $this->comments[] = $comment;
    }
}