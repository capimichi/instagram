<?php

namespace Capimichi\Instagram\Page;


use Capimichi\Instagram\ArrayReader;
use Capimichi\Instagram\Endpoints;
use Capimichi\Instagram\Request\AuthenticatedRequest;
use Unirest\Request;

class UserPage
{
    const BATCH_SIZE = 500;

    /**
     * @var array
     */
    protected $jsonData;

    /**
     * @var string
     */
    protected $username;


    public function __construct($username)
    {
        $this->username = $username;
    }


    /**
     * @return array
     */
    public function getJsonData()
    {
        if (!isset($this->jsonData)) {
            $this->jsonData = (array)json_decode(Request::get(Endpoints::getAccountJsonLink($this->username))->raw_body);

        }
        return $this->jsonData;
    }


    /**
     * @return string|null
     */
    public function getBiography()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "user/biography");
    }


    /**
     * Get followers (require login)
     *
     * @param int $count
     * @return array
     */
    public function getFollowers($count = null)
    {
        if (!$count) {
            $count = $this->getFollowersCount();
        }
        $count = min($count, $this->getFollowersCount());
        $url = Endpoints::FOLLOWERS_URL;
        $url = str_replace("{{accountId}}", $this->getId(), $url);
        $datas = [];
        $after = null;
        if ($count > self::BATCH_SIZE) {
            $factor = $count / self::BATCH_SIZE;
            $completeNumber = floor($factor);
            $incompleteNumber = $factor - $completeNumber;
            $blocks = [];
            for ($i = 1; $i <= $completeNumber; $i++) {
                array_push($blocks, self::BATCH_SIZE);
            }
            if ($incompleteNumber > 0) {
                array_push($blocks, self::BATCH_SIZE * $incompleteNumber);
            }
        } else {
            $blocks = [
                self::BATCH_SIZE,
            ];
        }
        foreach ($blocks as $block) {
            $toShow = $block;
            $endUrl = str_replace("{{count}}", $toShow, $url);
            if ($after) {
                $endUrl .= "&after=" . $after;
            }
            $response = AuthenticatedRequest::get($endUrl);
            $data = $response->raw_body;
            $data = json_decode($data, true);
            array_push($datas, $data);
            $after = ArrayReader::getNestedPath($data, "data/user/edge_followed_by/page_info/end_cursor");
        }

        $users = [];
        foreach ($datas as $data) {
            $edges = ArrayReader::getNestedPath($data, "data/user/edge_followed_by/edges");
            foreach ($edges as $edge) {
                $user = new UserPage($edge['node']['username']);
                array_push($users, $user);
            }
        }

        return $users;

    }

    /**
     * @return int|null
     */
    public function getFollowersCount()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "user/followed_by/count");
    }

    /**
     * @return int|null
     */
    public function getFollowedCount()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "user/follows/count");
    }

    /**
     * @return string|null
     */
    public function getFullName()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "user/full_name");
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return intval(ArrayReader::getNestedPath($this->getJsonData(), "user/id"));
    }

    /**
     * @return bool|null
     */
    public function isPrivate()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "user/is_private");
    }

    /**
     * @return bool|null
     */
    public function isVerified()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "user/is_verified");
    }

    /**
     * @return string|null
     */
    public function getProfilePicUrl($hd = false)
    {
        return ArrayReader::getNestedPath($this->getJsonData(), sprintf("user/profile_pic_url%s", $hd ? "_hd" : ""));
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }


    public function getImages($count = 10)
    {
        //https://www.instagram.com/graphql/query/?query_id=17888483320059182&variables=%7B%22id%22%3A%22217295520%22%2C%22first%22%3A12%2C%22after%22%3A%22AQDA2FYOdflt5LBt17bqA2h1RDvOM86E41ybukoJlwBRhU30m9B15zSppzw4t1NbyiCkDeUu6Nirn-pDosqKdyIhjG_XqJmmXdLwCtF1LXuqew%22%7D

        //https://www.instagram.com/graphql/query/?query_id=17888483320059182&variables=%7B%22id%22%3A%22217295520%22%2C%22first%22%3A12%2C%22after%22%3A%22AQBn6Z5LXWAlmI0ZnBYzRY7Fhm3UEPPXN58t8VuUJ85FLkx9PXjMgWEhEnCfKS_1h3ms8uEGwyCTZyN4ygSp2DBd_xwpJLUhJdWgQmLpAQw3BA%22%7D

    }
}