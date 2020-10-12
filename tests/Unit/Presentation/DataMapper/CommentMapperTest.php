<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Presentation\DataMapper;

use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Entity\Comment as PresentationComment;
use InSided\GetOnBoard\Presentation\DataMapper\CommentMapper;
use PHPUnit\Framework\TestCase;

class CommentMapperTest extends TestCase
{
    public function testCommentGetsMappedProperly()
    {
        $comment = new Comment('id');
        $comment->setText('my-comment');

        $presentationComment = CommentMapper::map($comment);
        $this->assertInstanceOf(PresentationComment::class, $presentationComment);
        $this->assertEquals('id', $presentationComment->getId());
        $this->assertEquals('my-comment', $presentationComment->getText());
    }
}
