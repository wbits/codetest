<?php

namespace InSided\GetOnBoard\Controller;

use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Entity\Post;

class ConversationController
{
    private CommunityRepositoryInterface $communityRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        CommunityRepositoryInterface $communityRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->communityRepository = $communityRepository;
        $this->userRepository = $userRepository;
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
        $posts = $community->getPosts();

        return $posts;
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
        $community = $this->communityRepository->getCommunity($communityId);
        $post = $community->addPost($title, $text, 'conversation');

        $user = $this->userRepository->getUser($userId);
        $user->addPost($post);

        return $post;
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
        $user = $this->userRepository->getUser($userId);
        /** @var Post $userPost */
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->getId() == $conversationId) {
                $community = $this->communityRepository->getCommunity($communityId);
                $post = $community->updatePost($conversationId, $title, $text);
            }
        }

        return $post;
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
        $user = $this->userRepository->getUser($userId);
        /** @var Post $userPost */
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->getId() == $conversationId) {
                $community = $this->communityRepository->getCommunity($communityId);
                $community->deletePost($conversationId);
            }
        }

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
        $community = $this->communityRepository->getCommunity($communityId);
        $comment = $community->addComment($conversationId, $text);

        $user = $this->userRepository->getUser($userId);
        $user->addComment($comment);

        return $comment;
    }
}
