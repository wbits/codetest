<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Services\CommandHandler;

use InSided\GetOnBoard\Core\Command\UpdateArticleCommand;
use InSided\GetOnBoard\Core\Event\ArticleUpdatedEvent;
use InSided\GetOnBoard\Core\Services\Message\Dispatcher\EventDispatcherInterface;

class UpdateArticleCommandHandler
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(UpdateArticleCommand $command)
    {
        $article = $command->getArticle();
        $article->setTitle($command->getTitle());
        $article->setText($command->getText());

        $this->eventDispatcher->dispatch(new ArticleUpdatedEvent($article));
    }
}
