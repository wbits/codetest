<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Repository;

use InSided\GetOnBoard\Entity\User;

interface UserRepositoryInterface
{
    public function getUser(string $id): ?User;

    public function addUser(User $user): void;
}
