<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Repository;

use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\User;

interface CommunityRepositoryInterface
{
    public function getCommunity(string $id): ?Community;

    public function addCommunity(Community $community): void;

    public function getUser(string $id): ?User;

    public function addUser(User $user): void;
}
