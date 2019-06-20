<?php

namespace InSided\GetOnBoard\Repository;

use InSided\GetOnBoard\Entity\Community;
use InSided\GetOnBoard\Entity\User;

class CommunityRepository
{
    private static $communities = [];
    private static $users = [];

    public static function getCommunity($id): Community
    {
        foreach (self::$communities as $community) {
            if ($community->id == $id) {
                return $community;
            }
        }

        return null;
    }

    public static function addCommunity(\InSided\GetOnBoard\Entity\Community $community)
    {
        self::$communities[] = $community;
    }

    public static function getUser($id): User
    {
        foreach (self::$users as $user) {
            if ($user->id == $id) {
                return $user;
            }
        }

        return null;
    }

    public static function addUser(\InSided\GetOnBoard\Entity\User $user)
    {
        self::$users[] = $user;
    }
}