<?php

namespace Capimichi\Instagram\Page;


class ImagePage extends Page
{

    /**
     * @param $code
     * @return ImagePage
     */
    public static function createFromCode($code)
    {
        $url = sprintf("https://www.instagram.com/p/%s/", $code);
        return new ImagePage($url);
    }

}