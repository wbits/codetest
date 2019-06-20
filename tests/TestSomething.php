<?php

namespace InSided\GetOnBoard;

use InSided\GetOnBoard\Controller\ArticleController;
use InSided\GetOnBoard\Controller\ConversationController;
use InSided\GetOnBoard\Controller\QuestionController;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\User;
use InSided\GetOnBoard\Repository\CommunityRepository;
use PHPUnit\Framework\TestCase;

class TestSomething extends TestCase
{
    /**
     * @test
     */
    public function dummy()
    {
        $community = new Community();
        CommunityRepository::addCommunity($community);

        $user = new User();
        $user->setUsername('john');
        CommunityRepository::addUser($user);

        $user2 = new User();
        $user2->setUsername('jane');
        CommunityRepository::addUser($user2);

        $controller = new QuestionController();
        $article = $controller->createAction($user->getId(), $community->getId(), 'foo', 'bar');
        $controller->updateAction($user->getId(), $community->getId(), $article->getId(), 'foo2', 'bar2');
        $community->deletePost($article->getId());
        $controller->commentAction($user2->getId(), $community->getId(), $article->getId(), 'jar');

        $this->assertTrue(true);
    }
}