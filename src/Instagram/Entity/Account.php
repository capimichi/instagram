<?php

namespace Capimichi\Instagram\Entity;


use Capimichi\Instagram\ArrayReader;
use Capimichi\Instagram\Endpoints;
use Capimichi\Instagram\Request\AuthenticatedCachedRequest;
use Capimichi\Instagram\Request\AuthenticatedRequest;
use Capimichi\Instagram\Request\CachedRequest;
use Unirest\Request;

class Account extends InstagramEntity
{
    const BATCH_SIZE_FOLLOWERS = 500;
    const BATCH_SIZE_MEDIA = 20;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $biography;

    /**
     * @var int
     */
    protected $followersCount;

    /**
     * @var int
     */
    protected $followedCount;

    /**
     * @var int
     */
    protected $mediasCount;

    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $private;

    /**
     * @var bool
     */
    protected $verified;

    /**
     * @var string
     */
    protected $profilePictureUrl;

    /**
     * @var string
     */
    protected $profilePictureUrlHD;

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
     * @return string
     */
    public function getUsername()
    {
        if (!isset($this->username)) {
            $this->username = ArrayReader::getNestedPath($this->getJsonData(), "user/username");
        }
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getBiography()
    {
        if (!isset($this->biography)) {
            $this->biography = ArrayReader::getNestedPath($this->getJsonData(), "user/biography");
        }
        return $this->biography;
    }

    /**
     * @param string $biography
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * @return int
     */
    public function getFollowersCount()
    {
        if (!isset($this->followersCount)) {
            $this->followersCount = ArrayReader::getNestedPath($this->getJsonData(), "user/followed_by/count");
        }
        return $this->followersCount;
    }

    /**
     * @param int $followersCount
     */
    public function setFollowersCount($followersCount)
    {
        $this->followersCount = $followersCount;
    }

    /**
     * @return int
     */
    public function getFollowedCount()
    {
        if (!isset($this->followedCount)) {
            $this->followedCount = ArrayReader::getNestedPath($this->getJsonData(), "user/follows/count");
        }
        return $this->followedCount;
    }

    /**
     * @param int $followedCount
     */
    public function setFollowedCount($followedCount)
    {
        $this->followedCount = $followedCount;
    }

    /**
     * @return int
     */
    public function getMediasCount()
    {
        if (!isset($this->mediasCount)) {
            $this->mediasCount = ArrayReader::getNestedPath($this->getJsonData(), "user/media/count");
        }
        return $this->mediasCount;
    }

    /**
     * @param int $mediasCount
     */
    public function setMediasCount($mediasCount)
    {
        $this->mediasCount = $mediasCount;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        if (!isset($this->fullName)) {
            $this->fullName = ArrayReader::getNestedPath($this->getJsonData(), "user/full_name");
        }
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if (!isset($this->id)) {
            $this->id = intval(ArrayReader::getNestedPath($this->getJsonData(), "user/id"));
        }
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        if (!isset($this->private)) {
            $this->private = ArrayReader::getNestedPath($this->getJsonData(), "user/is_private");
        }
        return $this->private;
    }

    /**
     * @param bool $private
     */
    public function setPrivate($private)
    {
        $this->private = $private;
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        if (!isset($this->verified)) {
            $this->verified = ArrayReader::getNestedPath($this->getJsonData(), "user/is_verified");
        }
        return $this->verified;
    }

    /**
     * @param bool $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * @return string
     */
    public function getProfilePictureUrl()
    {
        if (!isset($this->profilePictureUrl)) {
            $this->profilePictureUrl = ArrayReader::getNestedPath($this->getJsonData(), "user/profile_pic_url");
        }
        return $this->profilePictureUrl;
    }

    /**
     * @param string $profilePictureUrl
     */
    public function setProfilePictureUrl($profilePictureUrl)
    {
        $this->profilePictureUrl = $profilePictureUrl;
    }

    /**
     * @return string
     */
    public function getProfilePictureUrlHD()
    {
        if (!isset($this->profilePictureUrlHD)) {
            $this->profilePictureUrlHD = ArrayReader::getNestedPath($this->getJsonData(), "user/profile_pic_url_hd");
        }
        return $this->profilePictureUrlHD;
    }

    /**
     * @param string $profilePictureUrlHD
     */
    public function setProfilePictureUrlHD($profilePictureUrlHD)
    {
        $this->profilePictureUrlHD = $profilePictureUrlHD;
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
            $response = AuthenticatedCachedRequest::get($endUrl);
            $data = $response->raw_body;
            $data = json_decode($data, true);
            array_push($datas, $data);
            $after = ArrayReader::getNestedPath($data, "data/user/edge_followed_by/page_info/end_cursor");
        }

        $users = [];
        foreach ($datas as $data) {
            $edges = ArrayReader::getNestedPath($data, "data/user/edge_followed_by/edges");
            foreach ($edges as $edge) {
                $user = Account::createFromUsername($edge['node']['username']);
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
            $response = CachedRequest::get(Endpoints::getAccountMediasJsonLink($username, $maxId));
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

    /**
     * @inheritdoc
     */
    protected function getJsonLink()
    {
        return Endpoints::getAccountJsonLink($this->username);
    }


}