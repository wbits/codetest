<?php

namespace InSided\GetOnBoard\Entity;

class Post
{
    public $id;
    public $title;
    public $text;
    public $type;
    public $parent;
    public $comments;
    public $deleted;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->id =  uniqid();
        $this->comments = [];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @param $text
     * @return Comment
     */
    public function addComment($text)
    {
        $comment = new Comment();
        $comment->setText($text);

        $this->comments[] = $comment;

        return $comment;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     */
    public function setDeleted($deleted): void
    {
        $this->deleted = $deleted;
    }
}