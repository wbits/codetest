<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Core\Entity;

use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Entity\Post;
use PHPUnit\Framework\TestCase;

class CommunityTest extends TestCase
{
    public function testPostsAreFilteredCorrectly(): void
    {
        $article1 = new Post('id1', Post::TYPE_ARTICLE);
        $article2 = new Post('id2', Post::TYPE_ARTICLE);
        $article2->setDeleted(true);
        $conversation1 = new Post('id3', Post::TYPE_CONVERSATION);
        $conversation2 = new Post('id4', Post::TYPE_CONVERSATION);
        $conversation2->setDeleted(true);
        $question1 = new Post('id5', Post::TYPE_QUESTION);
        $question2 = new Post('id6', Post::TYPE_QUESTION);
        $question2->setDeleted(true);

        $community = new Community('id');
        $community->addPost($article1);
        $community->addPost($article2);
        $community->addPost($conversation1);
        $community->addPost($conversation2);
        $community->addPost($question1);
        $community->addPost($question2);

        $this->assertEquals(3, count($community->getPosts()));
        $this->assertEquals(1, count($community->getArticles()));
        $this->assertEquals(1, count($community->getQuestions()));
        $this->assertEquals(1, count($community->getConversations()));
    }
}
