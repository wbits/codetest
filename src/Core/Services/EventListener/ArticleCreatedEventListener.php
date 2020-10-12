<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Services\EventListener;

use InSided\GetOnBoard\Core\Event\ArticleCreatedEvent;

class ArticleCreatedEventListener
{
    public function handle(ArticleCreatedEvent $event): void
    {
        $article = $event->getArticle();

        $user = $article->getUser();
        $user->addPost($article);

        $community = $article->getCommunity();
        $community->addPost($article);
    }
}
