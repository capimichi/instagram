<?php

namespace Capimichi\Instagram\Entity;


use Capimichi\Instagram\ArrayReader;
use Capimichi\Instagram\Endpoints;

class Location extends InstagramEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $publicPage;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @param $id
     * @return Location
     */
    public static function createFromId($id)
    {
        $self = new self;
        $self->id = $id;
        return $self;
    }

    /**
     * @param $array
     * @return Location
     */
    public static function createFromArray($array)
    {
        $self = new self;
        $self->id = ArrayReader::getNestedPath($array, "location/id");
        return $self;
    }

    /**
     * @return string
     */
    public function getId()
    {
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
    public function isPublicPage()
    {
        return $this->publicPage;
    }

    /**
     * @param bool $publicPage
     */
    public function setPublicPage($publicPage)
    {
        $this->publicPage = $publicPage;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    public function getMedias($count = null)
    {
        //TODO: Location get medias
    }

    /**
     * @inheritDoc
     */
    protected function getJsonLink()
    {
        return Endpoints::getMediasJsonByLocationIdLink($this->id);
    }


}