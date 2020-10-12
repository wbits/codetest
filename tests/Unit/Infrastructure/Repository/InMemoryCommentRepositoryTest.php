<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Infrastructure\Repository\InMemoryCommentRepository;
use PHPUnit\Framework\TestCase;

class InMemoryCommentRepositoryTest extends TestCase
{
    private InMemoryCommentRepository $commentRepository;

    public function setUp(): void
    {
        $this->commentRepository = new InMemoryCommentRepository();
    }

    public function testCommentsCanBeFiltered(): void
    {
        $commentRepository = new InMemoryCommentRepository();

        $comment1 = new Comment('c1');
        $post1 = new Post('p1', Post::TYPE_CONVERSATION);
        $comment1->setParent($post1);
        $commentRepository->addComment($comment1);

        $comment2 = new Comment('c2');
        $post2 = new Post('p2', Post::TYPE_CONVERSATION);
        $comment2->setParent($post2);
        $commentRepository->addComment($comment2);

        $this->assertCount(1, $commentRepository->getCommentsForPost($post1));

    }
}
