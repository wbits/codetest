<?php

namespace InSided\GetOnBoard\Controller;

use InSided\GetOnBoard\Repository\CommunityRepository;

class QuestionController
{
    /**
     * @param $communityId
     * @return array
     *
     * POST insided.com/community/[user-id]/[community-id]/questions
     */
    public function listAction($communityId)
    {
        $community = CommunityRepository::getCommunity($communityId);
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
        $community = CommunityRepository::getCommunity($communityId);
        $post = $community->addPost($title, $text, 'question');

        $user = CommunityRepository::getUser($userId);
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
        $user = CommunityRepository::getUser($userId);
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->id == $questionId) {
                $community = CommunityRepository::getCommunity($communityId);
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
        $user = CommunityRepository::getUser($userId);
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->id == $questionId) {
                $community = CommunityRepository::getCommunity($communityId);
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
        $community = CommunityRepository::getCommunity($communityId);
        $comment = $community->addComment($questionId, $text);

        $user = CommunityRepository::getUser($userId);
        $user->addComment($comment);

        return $comment;
    }
}