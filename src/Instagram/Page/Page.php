<?php

namespace Capimichi\Instagram\Page;

abstract class Page
{

    /**
     * @var string
     */
    protected $url;

    /**
     * Page constructor.
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param $path
     * @return array|mixed|null
     * @throws \Exception
     */
    protected function getFromPath($path)
    {
//        $path = rtrim("/", ltrim("/", $path));
        $data = $this->getSharedData();
        $pathPieces = explode("/", $path);
        foreach ($pathPieces as $pathPiece) {
            if (array_key_exists($pathPiece, $data)) {
                $data = $data[$pathPiece];
                if (is_object($data)) {
                    $data = (array)$data;
                }
            } else {
                throw new \Exception(sprintf("Wrong path %s (%s), valid values are: %s", $path, $pathPiece, implode(", ", array_keys($data))));
            }
        }
        return $data;
    }

    /**
     * @return array|null
     */
    protected function getSharedData()
    {
        $content = $this->getContent();
        if (preg_match("/window._sharedData = (.*?);<\/script>/is", $content, $sharedData)) {
            $sharedData = $sharedData[1];
            $sharedData = json_decode($sharedData, true);

        } else {
            $sharedData = null;
        }
        return $sharedData;
    }

    /**
     * @return bool|string
     */
    protected function getContent()
    {
        return file_get_contents($this->url);
    }

}