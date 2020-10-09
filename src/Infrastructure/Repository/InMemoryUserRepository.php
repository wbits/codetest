<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Repository\UserRepositoryInterface;
use InSided\GetOnBoard\Entity\User;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private static array $users = [];

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
