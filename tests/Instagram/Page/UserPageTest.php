<?php

use Capimichi\Instagram\Page\UserPage;
use PHPUnit\Framework\TestCase;

class UserPageTest extends TestCase
{


    public function testCanParseData()
    {
        $userPage = new UserPage("https://www.instagram.com/capimichi2/");
        self::assertEmpty($userPage->getBiography());
        self::assertInternalType("int", $userPage->getFollowersCount());
        self::assertInternalType("int", $userPage->getFollowedCount());
    }
}