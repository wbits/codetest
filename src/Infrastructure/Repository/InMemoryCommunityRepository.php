<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Entity\Community;
use InSided\GetOnBoard\Core\Repository\CommunityRepositoryInterface;

class InMemoryCommunityRepository implements CommunityRepositoryInterface
{
    /**
     * @var Community[]
     */
    private static array $communities = [];

    public function getCommunity(string $id): ?Community
    {
        foreach (self::$communities as $community) {
            if ($community->getId() == $id) {
                return $community;
            }
        }

        return null;
    }

    public function addCommunity(Community $community): void
    {
        self::$communities[] = $community;
    }
}
