<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 29/07/17
 * Time: 19:17
 */

namespace Capimichi\Instagram\Entity;


use Capimichi\Instagram\Endpoints;
use Capimichi\Instagram\Request\CachedRequest;

abstract class InstagramEntity
{
    /**
     * @var array
     */
    protected $jsonData;

    /**
     * @return array
     */
    public function getJsonData()
    {
        if (!isset($this->jsonData)) {
            $this->jsonData = (array)json_decode(CachedRequest::get($this->getJsonLink())->raw_body);
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
    protected abstract function getJsonLink();


}