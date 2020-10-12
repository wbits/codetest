<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Repository;

use InSided\GetOnBoard\Core\Entity\User;
use InSided\GetOnBoard\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

class InMemoryUserRepositoryTest extends TestCase
{
    private InMemoryUserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = new InMemoryUserRepository();
    }

    public function testNullGetsReturnedIfUserNotFound(): void
    {
        $storedUser = $this->userRepository->getUser(uniqid());

        $this->assertNull($storedUser);
    }

    public function testStoredUserCanBeRetrieved(): void
    {
        $user = new User('id');
        $this->userRepository->addUser($user);
        $storedUser = $this->userRepository->getUser($user->getId());

        $this->assertSame($user, $storedUser);

    }
}
