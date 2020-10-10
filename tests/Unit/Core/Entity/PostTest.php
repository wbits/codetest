<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Core\Entity;

use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Exception\Post\InvalidPostTypeException;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testPostIsAnArticle()
    {
        $post = new Post('id', Post::TYPE_ARTICLE);
        $this->assertTrue($post->isArticle());
    }

    public function testPostIsAConversation()
    {
        $post = new Post('id', Post::TYPE_CONVERSATION);
        $this->assertTrue($post->isConversation());
    }

    public function testPostIsAQuestion()
    {
        $post = new Post('id', Post::TYPE_QUESTION);
        $this->assertTrue($post->isQuestion());
    }

    public function testPostCannotBeOfInvalidType()
    {
        $this->expectException(InvalidPostTypeException::class);

        new Post('id', 'invalid type');
    }
}
