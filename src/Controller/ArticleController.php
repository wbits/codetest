<?php

namespace InSided\GetOnBoard\Controller;

use InSided\GetOnBoard\Repository\CommunityRepository;

class ArticleController
{
    /**
     * @param $communityId
     * @return array
     *
     * POST insided.com/community/[user-id]/[community-id]/articles
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
     * POST insided.com/community/[user-id]/[community-id]/articles/[type]
     *
     */
    public function createAction($userId, $communityId, $title, $text)
    {
        $community = CommunityRepository::getCommunity($communityId);
        $post = $community->addPost($title, $text, 'article');

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
     * PUT insided.com/community/[user-id]/[community-id]/articles/[article-id]
     *
     */
    public function updateAction($userId, $communityId, $articleId, $title, $text)
    {
        $user = CommunityRepository::getUser($userId);
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->id == $articleId) {
                $community = CommunityRepository::getCommunity($communityId);
                $post = $community->updatePost($articleId, $title, $text);
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
     * DELETE insided.com/community/[user-id]/[community-id]/articles/[article-id]
     */
    public function deleteAction($userId, $communityId, $articleId)
    {
        $user = CommunityRepository::getUser($userId);
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->id == $articleId) {
                $community = CommunityRepository::getCommunity($communityId);
                $community->deletePost($articleId);
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
     * POST insided.com/community/[user-id]/[community-id]/articles/[article-id]
     */
    public function commentAction($userId, $communityId, $articleId, $text)
    {
        $community = CommunityRepository::getCommunity($communityId);
        $comment = $community->addComment($articleId, $text);

        $user = CommunityRepository::getUser($userId);
        $user->addComment($comment);

        return $comment;
    }
}