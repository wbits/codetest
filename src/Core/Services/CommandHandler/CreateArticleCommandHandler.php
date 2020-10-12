<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Services\CommandHandler;

use InSided\GetOnBoard\Core\Command\CreateArticleCommand;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Event\ArticleCreatedEvent;
use InSided\GetOnBoard\Core\Exception\Post\InvalidPostTypeException;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\PostRepositoryInterface;
use InSided\GetOnBoard\Core\Services\Message\Dispatcher\EventDispatcherInterface;

class CreateArticleCommandHandler
{
    private CommunityRepositoryInterface $communityRepository;
    private UserRepositoryInterface $userRepository;
    private PostRepositoryInterface $postRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        PostRepositoryInterface $postRepository,
        CommunityRepositoryInterface $communityRepository,
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->postRepository = $postRepository;
        $this->communityRepository = $communityRepository;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreateArticleCommand $command): void
    {
        try {
            $article = $this->createArticle($command);
        } catch (InvalidPostTypeException $e) {
            // log
            return;
        }

        $this->postRepository->addPost($article);
        $this->sendArticleCreatedEvent($article);
    }

    /**
     * @throws InvalidPostTypeException
     */
    private function createArticle(CreateArticleCommand $command): Post
    {
        $article = new Post($command->getArticleId(), Post::TYPE_ARTICLE);
        $article->setText($command->getText());
        $article->setTitle($command->getTitle());
        $article->setDeleted(false);
        $article->setCommentsAllowed(true);

        $user = $this->userRepository->getUser($command->getUserId());
        $article->setUser($user);

        $community = $this->communityRepository->getCommunity($command->getCommunityId());
        $article->setCommunity($community);

        return $article;
    }

    private function sendArticleCreatedEvent(Post $article): void
    {
        $articleCreatedEvent = new ArticleCreatedEvent($article);
        $this->eventDispatcher->dispatch($articleCreatedEvent);
    }
}
