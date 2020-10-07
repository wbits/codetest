<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure\Repository;

use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\User;
use InSided\GetOnBoard\Infrastructure\Repository\InMemoryCommunityRepository;
use PHPUnit\Framework\TestCase;

class InMemoryCommunityRepositoryTest extends TestCase
{
    private InMemoryCommunityRepository $communityRepository;

    public function setUp(): void
    {
        $this->communityRepository = new InMemoryCommunityRepository();
    }

    public function testNullGetsReturnedIfCommunityNotFound(): void
    {
        $storedCommunity = $this->communityRepository->getCommunity(uniqid());

        $this->assertNull($storedCommunity);
    }

    public function testStoredCommunityCanBeRetrieved(): void
    {
        $community = new Community();
        $this->communityRepository->addCommunity($community);
        $storedCommunity = $this->communityRepository->getCommunity($community->getId());

        $this->assertSame($community, $storedCommunity);
    }

    public function testNullGetsReturnedIfUserNotFound(): void
    {
        $storedUser = $this->communityRepository->getUser(uniqid());

        $this->assertNull($storedUser);
    }

    public function testStoredUserCanBeRetrieved(): void
    {
        $user = new User();
        $this->communityRepository->addUser($user);
        $storedUser = $this->communityRepository->getUser($user->getId());

        $this->assertSame($user, $storedUser);

    }
}
