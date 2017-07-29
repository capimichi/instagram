<?php

use Capimichi\Instagram\InstagramSession;
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

    public function testCanGetFollowers()
    {
        InstagramSession::setCredential("capimichi2", "Benten.10");
        InstagramSession::login();
        $userPage = new UserPage("chanelofficial");
        self::assertCount(500, $userPage->getFollowers(500));
        self::assertCount(700, $userPage->getFollowers(700));
        self::assertCount(1000, $userPage->getFollowers(1000));
    }
}