<?php

namespace InSided\GetOnBoard\Controller;

use InSided\GetOnBoard\Core\Entity\Comment;
use InSided\GetOnBoard\Core\Exception\Post\InvalidPostTypeException;
use InSided\GetOnBoard\Core\Repository\CommentRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\PostRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Core\Services\IdGeneratorInterface;
use InSided\GetOnBoard\Core\Entity\Post;
use InSided\GetOnBoard\Presentation\Services\EntityMapper;

class ConversationController
{

    private CommunityRepositoryInterface $communityRepository;

    private UserRepositoryInterface $userRepository;

    private PostRepositoryInterface $postRepository;

    private EntityMapper $entityMapper;

    private IdGeneratorInterface $idGenerator;

    private CommentRepositoryInterface $commentRepository;

    public function __construct(
        CommunityRepositoryInterface $communityRepository,
        UserRepositoryInterface $userRepository,
        PostRepositoryInterface $postRepository,
        CommentRepositoryInterface $commentRepository,
        EntityMapper $entityMapper,
        IdGeneratorInterface $idGenerator
    ) {
        $this->communityRepository = $communityRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->entityMapper = $entityMapper;
        $this->idGenerator = $idGenerator;
    }

    /**
     * @param $communityId
     * @return array
     *
     * POST insided.com/community/[user-id]/[community-id]/conversations
     */
    public function listAction($communityId)
    {
        $community = $this->communityRepository->getCommunity($communityId);
        if (!$community) {
            return [];
        }

        return array_map(
            [$this->entityMapper, 'map'],
            $this->postRepository->getConversationsByCommunity($community)
        );
    }

    /**
     * @param $communityId
     * @param $title
     * @param $text
     *
     * @return \InSided\GetOnBoard\Entity\Post|null
     *
     * POST insided.com/community/[user-id]/[community-id]/conversations/[type]
     *
     */
    public function createAction($userId, $communityId, $title, $text)
    {
        $postId = $this->idGenerator->generate();
        try {
            $post = new Post($postId, Post::TYPE_CONVERSATION);
        } catch (InvalidPostTypeException $e) {
            return null;
        }

        $community = $this->communityRepository->getCommunity($communityId);
        $user = $this->userRepository->getUser($userId);
        $post->setCommunity($community);
        $post->setUser($user);
        $post->setTitle($title);
        $post->setText($text);

        $this->postRepository->addPost($post);
        $community->addPost($post);
        $user->addPost($post);

        return $this->entityMapper->map($post);
    }

    /**
     * @param $communityId
     * @param $title
     * @param $text
     *
     * @return mixed
     *
     * PUT insided.com/community/[user-id]/[community-id]/conversations/[conversation-id]
     *
     */
    public function updateAction($userId, $communityId, $conversationId, $title, $text)
    {
        $post = $this->postRepository->getPost($conversationId);
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
     * DELETE insided.com/community/[user-id]/[community-id]/conversations/[conversation-id]
     */
    public function deleteAction($userId, $communityId, $conversationId)
    {
        $post = $this->postRepository->getPost($conversationId);
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
     * POST insided.com/community/[user-id]/[community-id]/conversations/[conversation-id]
     */
    public function commentAction($userId, $communityId, $conversationId, $text)
    {
        $post = $this->postRepository->getPost($conversationId);
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
}
