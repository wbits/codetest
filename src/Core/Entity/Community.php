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
        return array_filter(
            $this->posts,
            fn(Post $post) => !$post->isDeleted()
        );
    }

    /**
     * @return Post[]
     */
    public function getArticles(): array
    {
        return array_filter(
            $this->posts,
            fn(Post $post) => !$post->isDeleted() && $post->isArticle()
        );
    }

    /**
     * @return Post[]
     */
    public function getConversations(): array
    {
        return array_filter(
            $this->posts,
            fn(Post $post) => !$post->isDeleted() && $post->isConversation()
        );
    }

    /**
     * @return Post[]
     */
    public function getQuestions(): array
    {
        return array_filter(
            $this->posts,
            fn(Post $post) => !$post->isDeleted() && $post->isQuestion()
        );
    }
}
