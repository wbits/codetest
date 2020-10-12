<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Repository;

use InSided\GetOnBoard\Core\Entity\User;

interface UserRepositoryInterface
{
    public function getUser(string $id): ?User;

    public function addUser(User $user): void;
}
