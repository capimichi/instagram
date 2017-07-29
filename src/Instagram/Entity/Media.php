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
class Media extends InstagramEntity
{

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $dimensions;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var bool
     */
    protected $video;

    /**
     * @var array
     */
    protected $taggedUsers;

    /**
     * @var string
     */
    protected $caption;

    /**
     * @var bool
     */
    protected $captionEdited;

    /**
     * @var array
     */
    protected $comments;

    /**
     * @var int
     */
    protected $commentsCount;

    /**
     * @var bool
     */
    protected $commentsDisabled;

    /**
     * @var int
     */
    protected $takenTimestamp;

    /**
     * @var int
     */
    protected $likesCount;

    /**
     * @var array
     */
    protected $likingUsers;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var Account
     */
    protected $owner;


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
     * @return string
     */
    public function getCode()
    {
        if (!isset($this->code)) {
            $this->code = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/shortcode");
        }
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
        if (!isset($this->id)) {
            $this->id = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/id");
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
     * @return array
     */
    public function getDimensions()
    {
        if (!isset($this->dimensions)) {
            $this->dimensions = [
                ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/dimensions/width"),
                ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/dimensions/height"),
            ];
        }
        return $this->dimensions;
    }

    /**
     * @param array $dimensions
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if (!isset($this->url)) {
            $this->url = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/display_url");
        }
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        if (!isset($this->video)) {
            $this->video = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/is_video");
        }
        return $this->video;
    }

    /**
     * @param bool $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return array
     */
    public function getTaggedUsers()
    {
        if (!isset($this->taggedUsers)) {
            $this->taggedUsers = [];
            $edges = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/edge_media_to_tagged_user/edges");
            foreach ($edges as $edge) {
                $user = Account::createFromUsername(ArrayReader::getNestedPath($edge, "node/user/username"));
                array_push($this->taggedUsers, $user);
            }
        }
        return $this->taggedUsers;
    }

    /**
     * @param array $taggedUsers
     */
    public function setTaggedUsers($taggedUsers)
    {
        $this->taggedUsers = $taggedUsers;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        if (!isset($this->code)) {
            $this->code = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/edge_media_to_caption/edges/0/node/text");
        }
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return bool
     */
    public function isCaptionEdited()
    {
        if (!isset($this->captionEdited)) {
            $this->captionEdited = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/caption_is_edited");
        }
        return $this->captionEdited;
    }

    /**
     * @param bool $captionEdited
     */
    public function setCaptionEdited($captionEdited)
    {
        $this->captionEdited = $captionEdited;
    }

    /**
     * @return array
     */
    public function getComments()
    {
        if (!isset($this->comments)) {
            //TODO: Get comments media
        }
        return $this->comments;
    }

    /**
     * @param array $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getCommentsCount()
    {
        if (!isset($this->commentsCount)) {
            $this->commentsCount = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/edge_media_to_comment/count");
        }
        return $this->commentsCount;
    }

    /**
     * @param int $commentsCount
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;
    }

    /**
     * @return bool
     */
    public function isCommentsDisabled()
    {
        if (!isset($this->commentsDisabled)) {
            $this->commentsDisabled = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/comments_disabled");
        }
        return $this->commentsDisabled;
    }

    /**
     * @param bool $commentsDisabled
     */
    public function setCommentsDisabled($commentsDisabled)
    {
        $this->commentsDisabled = $commentsDisabled;
    }

    /**
     * @return int
     */
    public function getTakenTimestamp()
    {
        if (!isset($this->takenTimestamp)) {
            $this->takenTimestamp = intval(ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/taken_at_timestamp"));
        }
        return $this->takenTimestamp;
    }

    /**
     * @param int $takenTimestamp
     */
    public function setTakenTimestamp($takenTimestamp)
    {
        $this->takenTimestamp = $takenTimestamp;
    }

    /**
     * @return int
     */
    public function getLikesCount()
    {
        if (!isset($this->likesCount)) {
            $this->likesCount = ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/edge_media_preview_like/count");
        }
        return $this->likesCount;
    }

    /**
     * @param int $likesCount
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    }

    /**
     * @return array
     */
    public function getLikingUsers()
    {
        if (!isset($this->likingUsers)) {
            //TODO: Media liking users
        }
        return $this->likingUsers;
    }

    /**
     * @param array $likingUsers
     */
    public function setLikingUsers($likingUsers)
    {
        $this->likingUsers = $likingUsers;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        if (!isset($this->location)) {
            try {
                $this->location = Location::createFromId(rrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/location/id"));
            } catch (\Exception $exception) {

            }
        }
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return Account
     */
    public function getOwner()
    {
        if (!isset($this->owner)) {
            $this->owner = Account::createFromUsername(ArrayReader::getNestedPath($this->getJsonData(), "graphql/shortcode_media/owner/username"));
        }
        return $this->owner;
    }

    /**
     * @param Account $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @inheritDoc
     */
    protected function getJsonLink()
    {
        return Endpoints::getMediaJsonLink($this->code);
    }


}