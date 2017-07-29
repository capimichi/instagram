<?php

namespace Capimichi\Instagram\Entity;


use Capimichi\Instagram\ArrayReader;
use Capimichi\Instagram\Endpoints;
use Capimichi\Instagram\Request\AuthenticatedRequest;
use Unirest\Request;

class Account
{
    const BATCH_SIZE_FOLLOWERS = 500;
    const BATCH_SIZE_MEDIA = 20;

    /**
     * @var array
     */
    protected $jsonData;

    /**
     * @var string
     */
    protected $username;


    /**
     * @param $username
     * @return Account
     */
    public static function createFromUsername($username)
    {
        $account = new self;
        $account->username = $username;

        return $account;
    }

    /**
     * @param $array
     * @return Account
     */
    public static function createFromArray($array)
    {
        $account = new self;
        switch (true) {
            case array_key_exists("code", $array):

                break;
            case array_key_exists("graphql", $array):

                break;

            default:

                break;
        }

        $account->jsonData = $array;
        $account->username = ArrayReader::getNestedPath($array, "user/username");
        return $account;
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
     * @return int|null
     */
    public function getMediasCount()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "user/media/count");
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

    /**
     * @param array $jsonData
     */
    public function setJsonData($jsonData)
    {
        $this->jsonData = $jsonData;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
        if ($count > self::BATCH_SIZE_FOLLOWERS) {
            $factor = $count / self::BATCH_SIZE_FOLLOWERS;
            $completeNumber = floor($factor);
            $incompleteNumber = $factor - $completeNumber;
            $blocks = [];
            for ($i = 1; $i <= $completeNumber; $i++) {
                array_push($blocks, self::BATCH_SIZE_FOLLOWERS);
            }
            if ($incompleteNumber > 0) {
                array_push($blocks, self::BATCH_SIZE_FOLLOWERS * $incompleteNumber);
            }
        } else {
            $blocks = [
                self::BATCH_SIZE_FOLLOWERS,
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
                $user = new Account($edge['node']['username']);
                array_push($users, $user);
            }
        }

        return $users;
    }


    /**
     * @param int $count
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getMedias($count = null)
    {
        if (!$count) {
            $count = $this->getMediasCount();
        }
        $username = $this->getUsername();
        $maxId = "";
        $index = 0;
        $medias = [];
        $isMoreAvailable = true;
        while ($index < $count && $isMoreAvailable) {
            $response = Request::get(Endpoints::getAccountMediasJsonLink($username, $maxId));
            if ($response->code !== 200) {
                throw new \Exception('Response code is ' . $response->code . '. Body: ' . $response->body . ' Something went wrong. Please report issue.');
            }

            $arr = json_decode($response->raw_body, true);
            if (!is_array($arr)) {
                throw new \Exception('Response code is ' . $response->code . '. Body: ' . $response->body . ' Something went wrong. Please report issue.');
            }

            if (empty($arr['items']) || !isset($arr['items'])) {
                return [];
            }

            foreach ($arr['items'] as $mediaArray) {
                if ($index === $count) {
                    return $medias;
                }
                $medias[] = Media::createFromCode($mediaArray["code"]);
                $index++;
            }
            if (empty($arr['items']) || !isset($arr['items'])) {
                return $medias;
            }
            $maxId = $arr['items'][count($arr['items']) - 1]['id'];
            $isMoreAvailable = $arr['more_available'];
        }
        return $medias;
    }
}