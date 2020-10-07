<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;
use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\User;

class InMemoryCommunityRepository implements CommunityRepositoryInterface
{
    /**
     * @var Community[]
     */
    private static array $communities = [];

    /**
     * @var User[]
     */
    private static array $users = [];

    public function getCommunity(string $id): ?Community
    {
        foreach (self::$communities as $community) {
            if ($community->id == $id) {
                return $community;
            }
        }

        return null;
    }

    public function addCommunity(Community $community): void
    {
        self::$communities[] = $community;
    }

    public function getUser(string $id): ?User
    {
        foreach (self::$users as $user) {
            if ($user->id == $id) {
                return $user;
            }
        }

        return null;
    }

    public function addUser(User $user): void
    {
        self::$users[] = $user;
    }
}
