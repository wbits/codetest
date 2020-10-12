<?php

namespace InSided\GetOnBoard\Presentation\Entity;

class Comment
{
    private string $id;
    private string $text;

    public function __construct(string $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
