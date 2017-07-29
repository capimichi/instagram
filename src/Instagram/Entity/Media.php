<?php

namespace Capimichi\Instagram\Entity;


use Capimichi\Instagram\ArrayReader;
use Capimichi\Instagram\Endpoints;
use Capimichi\Instagram\Request\AuthenticatedRequest;
use Unirest\Request;

/**
 * Class Media
 * @package Capimichi\Instagram\Entity
 */
class Media
{
    /**
     * @var array
     */
    protected $jsonData;

    /**
     * @var string
     */
    protected $code;

    /**
     * @param $array
     *
     * @return Media
     */
    public static function createFromArray($array)
    {
        $media = new self;
        $media->jsonData = $array;
        return $media;
    }

    /**
     * @param $code
     * @return Media
     */
    public static function createFromCode($code)
    {
        $media = new self;
        $media->code = $code;

        return $media;
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        if (!isset($this->jsonData)) {
            $this->jsonData = (array)json_decode(Request::get(Endpoints::getMediaJsonLink($this->code))->raw_body, true);

        }
        return $this->jsonData;
    }

    /**
     * @param array $jsonData
     */
    public function setJsonData($jsonData)
    {
        $this->jsonData = $jsonData;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/id");
    }

    /**
     * @return \stdClass
     */
    public function getDimensions()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/dimensions");
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/display_url");
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/is_video");
    }

    /**
     * @return array
     */
    public function getTaggedUsers()
    {
        $users = [];
        $edges = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/edge_media_to_tagged_user/edges");
        foreach ($edges as $edge) {
            $user = Account::createFromUsername(ArrayReader::getNestedPath($edge, "node/user/username"));
            array_push($users, $user);
        }
        return $users;
    }

    /**
     * @return string|null
     */
    public function getCaption()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/edge_media_to_caption/edges/0/node/text");
    }

    /**
     * @return bool
     */
    public function isCaptionEdited()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/caption_is_edited");
    }

    /**
     *
     */
    public function getComments()
    {
        //TODO: Get comments (test if many comments)
    }

    /**
     * @return bool
     */
    public function isCommentsDisabled()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/comments_disabled");
    }

    /**
     * @return int
     */
    public function getTakenTimestamp()
    {
        return intval(ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/taken_at_timestamp"));
    }

    /**
     * @return int
     */
    public function getLikesCount()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/edge_media_preview_like/count");
    }

    /**
     * @return string|null
     */
    public function getLocation()
    {
        return ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/location");
    }

    /**
     * @return Account
     */
    public function getOwner()
    {
        $owner = Account::createFromUsername(ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/owner/username"));
        return $owner;
    }

}