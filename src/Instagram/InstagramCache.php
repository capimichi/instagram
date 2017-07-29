<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 30/07/17
 * Time: 00:03
 */

namespace Capimichi\Instagram;


use phpFastCache\CacheManager;
use phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface;

class InstagramCache
{

    /**
     * @var ExtendedCacheItemPoolInterface
     */
    protected static $cache;

    /**
     * @var string
     */
    protected static $path;

    /**
     * @return ExtendedCacheItemPoolInterface
     */
    public static function getCache()
    {
        if (!isset(self::$path)) {
            self::setPath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR);
        }

        return self::$cache;
    }

    /**
     * @param string $path
     */
    public static function setPath($path)
    {
        self::$path = $path;

        CacheManager::setDefaultConfig(array(
            "path" => self::$path,
        ));

        self::$cache = CacheManager::getInstance('files');
    }


}