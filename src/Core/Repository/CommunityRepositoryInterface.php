<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Repository;

use InSided\GetOnBoard\Core\Entity\Community;

interface CommunityRepositoryInterface
{
    public function getCommunity(string $id): ?Community;

    public function addCommunity(Community $community): void;
}
