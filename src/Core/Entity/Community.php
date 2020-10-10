<?php

namespace InSided\GetOnBoard\Core\Entity;

class Community
{
    private string $id;
    private string $name;

    /**
     * @var Post[]
     */
    private array $posts = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function addPost(Post $post): void
    {
        $this->posts[] = $post;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return array_map(
            fn(Post $post) => !$post->isDeleted(),
            $this->posts
        );
    }
}
