<?php

namespace InSided\GetOnBoard\Controller;

use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Entity\Post;

class QuestionController
{
    private CommunityRepositoryInterface $communityRepository;

    public function __construct(CommunityRepositoryInterface $communityRepository)
    {
        $this->communityRepository = $communityRepository;
    }
    /**
     * @param $communityId
     * @return array
     *
     * POST insided.com/community/[user-id]/[community-id]/questions
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
     * POST insided.com/community/[user-id]/[community-id]/questions/[type]
     *
     */
    public function createAction($userId, $communityId, $title, $text)
    {
        $community = $this->communityRepository->getCommunity($communityId);
        $post = $community->addPost($title, $text, 'question');

        $user = $this->communityRepository->getUser($userId);
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
     * PUT insided.com/community/[user-id]/[community-id]/questions/[question-id]
     *
     */
    public function updateAction($userId, $communityId, $questionId, $title, $text)
    {
        $user = $this->communityRepository->getUser($userId);
        /** @var Post $userPost */
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->getId() == $questionId) {
                $community = $this->communityRepository->getCommunity($communityId);
                $post = $community->updatePost($questionId, $title, $text);
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
     * DELETE insided.com/community/[user-id]/[community-id]/questions/[question-id]
     */
    public function deleteAction($userId, $communityId, $questionId)
    {
        $user = $this->communityRepository->getUser($userId);
        /** @var Post $userPost */
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->getId() == $questionId) {
                $community = $this->communityRepository->getCommunity($communityId);
                $community->deletePost($questionId);
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
     * POST insided.com/community/[user-id]/[community-id]/questions/[question-id]
     */
    public function commentAction($userId, $communityId, $questionId, $text)
    {
        $community = $this->communityRepository->getCommunity($communityId);
        $comment = $community->addComment($questionId, $text);

        $user = $this->communityRepository->getUser($userId);
        $user->addComment($comment);

        return $comment;
    }
}
