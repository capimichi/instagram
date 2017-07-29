<?php

namespace Capimichi\Instagram\Request;

use Capimichi\Instagram\InstagramCache;
use Capimichi\Instagram\InstagramSession;
use phpFastCache\CacheManager;
use Unirest\Request;

/**
 * Class CachedRequest
 * @package Capimichi\Instagram\Request
 */
class CachedRequest extends Request
{
    /**
     * @param string $url
     * @param int $expiration
     * @param array $headers
     * @param null $parameters
     * @param null $username
     * @param null $password
     * @return \Unirest\Response
     */
    public static function get($url, $expiration = 172800, $headers = array(), $parameters = null, $username = null, $password = null)
    {
        $cache = InstagramCache::getCache();
        $cacheKey = md5($url);
        $cachedString = $cache->getItem($cacheKey);
        $data = $cachedString->get();
        if (is_null($data)) {
            $response = parent::get($url, $headers, $parameters, $username, $password);
            if ($response->code == 200) {
                $cachedString->set($response)->expiresAfter($expiration);
                $cache->save($cachedString);
            }
        } else {
            $response = $data;
        }
        return $response;
    }


}