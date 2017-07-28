<?php

use Capimichi\Instagram\Page\UserPage;
use PHPUnit\Framework\TestCase;

class UserPageTest extends TestCase
{


    public function testCanParseData()
    {
        $userPage = new UserPage("capimichi2");
        self::assertEmpty($userPage->getBiography());
        self::assertInternalType("int", $userPage->getFollowersCount());
        self::assertInternalType("int", $userPage->getFollowedCount());
        self::assertNotEmpty($userPage->getFullName());
        self::assertInternalType('string', $userPage->getFullName());
        self::assertNotEmpty($userPage->getId());
        self::assertInternalType('int', $userPage->getId());
        self::assertInternalType('bool', $userPage->isPrivate());
        self::assertInternalType('bool', $userPage->isVerified());
        self::assertNotEmpty($userPage->getProfilePicUrl());
        self::assertInternalType('string', $userPage->getProfilePicUrl());
        self::assertNotEmpty($userPage->getUsername());
        self::assertInternalType('string', $userPage->getUsername());
    }

    public function testCanFetchFollowers()
    {
        // https://www.instagram.com/chanelofficial/
//        $userPage = new UserPage("https://www.instagram.com/chanelofficial/");
//        self::assertCount(700, $userPage->getFollowers(700));
    }

    public function testCanLoginAndGetFollowers()
    {
        $userPage = new UserPage("chanelofficial");
        $userPage->login("capimichi2", "Benten.10");
        self::assertCount(500, $userPage->getFollowers(500));
    }
}