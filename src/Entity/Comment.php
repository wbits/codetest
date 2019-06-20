<?php

namespace InSided\GetOnBoard\Entity;

class Comment
{
    public $id;
    public $text;

    public function __construct()
    {
        $this->id =  uniqid();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }
}