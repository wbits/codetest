<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Controller;

use InSided\GetOnBoard\Core\Command\CreateArticleCommand;
use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Exception\Post\InvalidPostTypeException;
use InSided\GetOnBoard\Core\Repository\CommentRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\PostRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Core\Services\IdGeneratorInterface;
use InSided\GetOnBoard\Core\Services\Message\Dispatcher\CommandDispatcherInterface;
use InSided\GetOnBoard\Presentation\Services\EntityMapper;

class ArticleController
{
    private CommunityRepositoryInterface $communityRepository;

    private UserRepositoryInterface $userRepository;

    private PostRepositoryInterface $postRepository;

    private EntityMapper $entityMapper;

    private IdGeneratorInterface $idGenerator;

    private CommentRepositoryInterface $commentRepository;

    private CommandDispatcherInterface $commandDispatcher;

    public function __construct(
        CommunityRepositoryInterface $communityRepository,
        UserRepositoryInterface $userRepository,
        PostRepositoryInterface $postRepository,
        CommentRepositoryInterface $commentRepository,
        EntityMapper $entityMapper,
        IdGeneratorInterface $idGenerator,
        CommandDispatcherInterface $commandDispatcher
    ) {
        $this->communityRepository = $communityRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->entityMapper = $entityMapper;
        $this->idGenerator = $idGenerator;
        $this->commandDispatcher = $commandDispatcher;
    }

    /**
     * @param $communityId
     * @return array
     *
     * POST insided.com/community/[user-id]/[community-id]/articles
     */
    public function listAction($communityId)
    {
        $community = $this->communityRepository->getCommunity($communityId);
        if (!$community) {
            return [];
        }

        return array_map(
            [$this->entityMapper, 'map'],
            $this->postRepository->getArticlesByCommunity($community)
        );
    }

    /**
     * @param $userId
     * @param $communityId
     * @param $title
     * @param $text
     *
     * @return \InSided\GetOnBoard\Presentation\Entity\Post|null
     *
     * POST insided.com/community/[user-id]/[community-id]/articles/[type]
     */
    public function createAction($userId, $communityId, $title, $text)
    {
        $newArticleId = $this->idGenerator->generate();
        $createArticleCommand = new CreateArticleCommand(
            $newArticleId,
            $communityId,
            $userId,
            $title,
            $text,
        );
        $this->commandDispatcher->dispatch($createArticleCommand);

        return $this->entityMapper->map($this->postRepository->getPost($newArticleId));
    }

    /**
     * @param $communityId
     * @param $title
     * @param $text
     *
     * @return mixed
     *
     * PUT insided.com/community/[user-id]/[community-id]/articles/[article-id]
     *
     */
    public function updateAction($userId, $communityId, $articleId, $title, $text)
    {
        $post = $this->postRepository->getPost($articleId);
        if (!$post) {
            return null;
        }

        $post->setText($text);
        $post->setTitle($title);

        return $this->entityMapper->map($post);
    }

    /**
     * @param $communityId
     * @param $title
     * @param $text
     *
     * @return null
     *
     * DELETE insided.com/community/[user-id]/[community-id]/articles/[article-id]
     */
    public function deleteAction($userId, $communityId, $articleId)
    {
        $post = $this->postRepository->getPost($articleId);
        if (!$post) {
            return null;
        }

        $post->setDeleted(true);

        return null;
    }

    /**
     * @param $communityId
     * @param $title
     * @param $text
     * @return mixed
     *
     * POST insided.com/community/[user-id]/[community-id]/articles/[article-id]
     */
    public function commentAction($userId, $communityId, $articleId, $text)
    {
        $post = $this->postRepository->getPost($articleId);
        if (!$post) {
            return null;
        }

        $commentId = $this->idGenerator->generate();
        $comment = new Comment($commentId);
        $comment->setText($text);
        $comment->setParent($post);

        $user = $this->userRepository->getUser($userId);
        $comment->setUser($user);

        $this->commentRepository->addComment($comment);
        $post->addComment($comment);

        return $this->entityMapper->map($comment);
    }

    /**
     * @param $communityId
     * @param $articleId
     *
     * PATCH insided.com/community/[community-id]/articles/[article-id]/disableComments
     */
    public function disableCommentsAction($communityId, $articleId)
    {
        $post = $this->postRepository->getPost($articleId);
        if (!$post) {
            return null;
        }

        $post->setCommentsAllowed(false);
    }
}
