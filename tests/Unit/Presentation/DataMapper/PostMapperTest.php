<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Presentation\DataMapper;

use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Entity\Comment as PresentationComment;
use InSided\GetOnBoard\Entity\Post as PresentationPost;
use InSided\GetOnBoard\Presentation\DataMapper\PostMapper;
use PHPUnit\Framework\TestCase;

class PostMapperTest extends TestCase
{
    public function testPostGetsMappedProperly()
    {
        $comment = new Comment('c');
        $comment->setText('my-comment');
        $post = new Post('p', Post::TYPE_ARTICLE);
        $post->setText('content');
        $post->setTitle('article');
        $post->addComment($comment);

        $presentationPost = PostMapper::map($post);
        $this->assertInstanceOf(PresentationPost::class, $presentationPost);
        $this->assertEquals('p', $presentationPost->getId());
        $this->assertEquals('article', $presentationPost->getTitle());
        $this->assertEquals('content', $presentationPost->getText());
        $this->assertEquals('article', $presentationPost->getType());
        $this->assertCount(1, $presentationPost->getComments());
        $this->assertInstanceOf(PresentationComment::class, $presentationPost->getComments()[0]);
    }
}
