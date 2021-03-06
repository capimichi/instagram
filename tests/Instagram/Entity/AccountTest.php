<?php

use Capimichi\Instagram\InstagramSession;
use Capimichi\Instagram\Entity\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{


    public function testCanParseData()
    {
        $userPage = Account::createFromUsername("capimichi2");
        self::assertEmpty($userPage->getBiography());
        self::assertInternalType("int", $userPage->getFollowersCount());
        self::assertInternalType("int", $userPage->getFollowedCount());
        self::assertNotEmpty($userPage->getFullName());
        self::assertInternalType('string', $userPage->getFullName());
        self::assertNotEmpty($userPage->getId());
        self::assertInternalType('int', $userPage->getId());
        self::assertInternalType('bool', $userPage->isPrivate());
        self::assertInternalType('bool', $userPage->isVerified());
        self::assertNotEmpty($userPage->getProfilePictureUrl());
        self::assertInternalType('string', $userPage->getProfilePictureUrl());
        self::assertNotEmpty($userPage->getProfilePictureUrlHD());
        self::assertInternalType('string', $userPage->getProfilePictureUrlHD());
        self::assertNotEmpty($userPage->getUsername());
        self::assertInternalType('string', $userPage->getUsername());
    }

    public function testCanGetFollowers()
    {
        InstagramSession::setCredential("capimichi2", "briscola2");
        InstagramSession::login();
        $userPage = Account::createFromUsername("chanelofficial");
        $followers = $userPage->getFollowers(500);
        self::assertCount(500, $followers);
        foreach ($followers as $follower) {
            self::assertNotEmpty($follower->getUsername());
        }
        $followers = $userPage->getFollowers(700);
        self::assertCount(700, $followers);
        foreach ($followers as $follower) {
            self::assertNotEmpty($follower->getUsername());
        }
        $followers = $userPage->getFollowers(1114);
        self::assertCount(1114, $followers);
        foreach ($followers as $follower) {
            self::assertNotEmpty($follower->getUsername());
        }
    }

    public function testCanGetMedias()
    {
//        InstagramSession::setCredential("capimichi2", "Benten.10");
//        InstagramSession::login();
        $userPage = Account::createFromUsername("chanelofficial");
        self::assertCount(20, $userPage->getMedias(20));
        self::assertCount(100, $userPage->getMedias(100));
        self::assertCount(200, $userPage->getMedias(200));
    }
}