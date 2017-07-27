<?php

namespace Capimichi\Instagram\Page;


class UserPage extends Page
{

    /**
     * @param $username
     * @return UserPage
     */
    public static function createFromUsername($username)
    {
        $url = sprintf("https://www.instagram.com/%s/", $username);
        return new UserPage($url);
    }

    /**
     * @return array|mixed|null
     */
    public function getBiography()
    {
        return $this->getFromPath("entry_data/ProfilePage/0/user/biography");
    }

    /**
     * @return array|mixed|null
     */
    public function getFollowersCount()
    {
        return $this->getFromPath("entry_data/ProfilePage/0/user/followed_by/count");
    }

    /**
     * @return array|mixed|null
     */
    public function getFollowedCount()
    {
        return $this->getFromPath("entry_data/ProfilePage/0/user/follows/count");
    }
}