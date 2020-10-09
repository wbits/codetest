<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Controller;

use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Entity\Post;

class ArticleController
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
     * POST insided.com/community/[user-id]/[community-id]/articles
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
     * POST insided.com/community/[user-id]/[community-id]/articles/[type]
     *
     */
    public function createAction($userId, $communityId, $title, $text)
    {
        $community = $this->communityRepository->getCommunity($communityId);
        $post = $community->addPost($title, $text, 'article');

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
     * PUT insided.com/community/[user-id]/[community-id]/articles/[article-id]
     *
     */
    public function updateAction($userId, $communityId, $articleId, $title, $text)
    {
        $user = $this->userRepository->getUser($userId);
        /** @var Post $userPost */
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->getId() == $articleId) {
                $community = $this->communityRepository->getCommunity($communityId);
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
        $user = $this->userRepository->getUser($userId);
        foreach ($user->getPosts() as $userPost) {
            if ($userPost->id == $articleId) {
                $community = $this->communityRepository->getCommunity($communityId);
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
        $community = $this->communityRepository->getCommunity($communityId);
        $comment = $community->addComment($articleId, $text);

        $user = $this->userRepository->getUser($userId);
        $user->addComment($comment);

        return $comment;
    }

    /**
     * @param $communityId
     * @param $articleId
     *
     * PATCH insided.com/community/[community-id]/articles/[article-id]/disableComments
     */
    public function disableCommentsAction($communityId, $articleId)
    {
        $community = $this->communityRepository->getCommunity($communityId);
        $community->disableCommentsForArticle($articleId);
    }
}
