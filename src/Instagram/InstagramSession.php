<?php
/**
 * Created by PhpStorm.
 * User: michelecapicchioni
 * Date: 28/07/17
 * Time: 10:14
 */

namespace Capimichi\Instagram;

use phpFastCache\CacheManager;
use phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface;
use Unirest\Request;

/**
 * Class InstagramSession that contains session data (for login)
 * @package Capimichi\Instagram
 */
class InstagramSession
{

    /**
     * @var mixed
     */
    protected static $userSession;

    /**
     * @var string
     */
    protected static $userName;

    /**
     * @var string
     */
    protected static $userPassword;

    /**
     * @var ExtendedCacheItemPoolInterface
     */
    protected static $cache;

    /**
     * @return mixed
     */
    public static function getUserSession()
    {
        return self::$userSession;
    }

    /**
     * @param mixed $userSession
     */
    public static function setUserSession($userSession)
    {
        self::$userSession = $userSession;
    }

    /**
     * @return string
     */
    public static function getUserName()
    {
        return self::$userName;
    }

    /**
     * @return string
     */
    public static function getUserPassword()
    {
        return self::$userPassword;
    }

    /**
     * @param $username
     * @param $password
     */
    public static function setCredential($username, $password)
    {
        self::$userName = $username;
        self::$userPassword = $password;

        CacheManager::setDefaultConfig(array(
            "path" => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . "sessions" . DIRECTORY_SEPARATOR,
        ));
        self::$cache = CacheManager::getInstance('files');
    }

    /**
     *
     * @throws \Exception
     */
    public static function login()
    {
        $cachedString = self::$cache->getItem('session');
        if (!self::isLoggedIn()) {
            $response = Request::get(Endpoints::BASE_URL);
            if ($response->code !== 200) {
                throw new \Exception('Response code is ' . $response->code . '. Body: ' . $response->body . ' Something went wrong. Please report issue.');
            }
            $cookies = self::parseCookies($response->headers['Set-Cookie']);
            $mid = $cookies['mid'];
            $csrfToken = $cookies['csrftoken'];
            $headers = ['cookie'      => "csrftoken=$csrfToken; mid=$mid;",
                        'referer'     => Endpoints::BASE_URL . '/',
                        'x-csrftoken' => $csrfToken,
            ];
            $response = Request::post(Endpoints::LOGIN_URL, $headers,
                ['username' => self::getUserName(), 'password' => self::getUserPassword()]);

            if ($response->code !== 200) {
                if ((is_string($response->code) || is_numeric($response->code)) && is_string($response->body)) {
                    throw new \Exception('Response code is ' . $response->code . '. Body: ' . $response->body . ' Something went wrong. Please report issue.');
                } else {
                    throw new \Exception('Something went wrong. Please report issue.');
                }
            }

            $cookies = self::parseCookies($response->headers['Set-Cookie']);
            $cookies['mid'] = $mid;
            $cachedString->set($cookies)->expiresAfter(86400);
            self::$cache->save($cachedString);
            self::setUserSession($cookies);
        } else {
            $session = $cachedString->get();
            self::setUserSession($session);
        }
    }

    /**
     * @return bool
     */
    public static function isLoggedIn()
    {
        $session = self::getUserSession();
        if (is_null($session) || !isset($session['sessionid'])) {
            return false;
        }
        $sessionId = $session['sessionid'];
        $csrfToken = $session['csrftoken'];
        $headers = ['cookie'      => "csrftoken=$csrfToken; sessionid=$sessionId;",
                    'referer'     => Endpoints::BASE_URL . '/',
                    'x-csrftoken' => $csrfToken,
        ];
        $response = Request::get(Endpoints::BASE_URL, $headers);
        if ($response->code !== 200) {
            return false;
        }
        $cookies = self::parseCookies($response->headers['Set-Cookie']);
        if (!isset($cookies['ds_user_id'])) {
            return false;
        }
        return true;
    }

    /**
     * @param string $rawCookies
     *
     * @return array
     */
    public static function parseCookies($rawCookies)
    {
        if (!is_array($rawCookies)) {
            $rawCookies = [$rawCookies];
        }

        $cookies = [];
        foreach ($rawCookies as $c) {
            $c = explode(';', $c)[0];
            $parts = explode('=', $c);
            if (sizeof($parts) >= 2 && !is_null($parts[1])) {
                $cookies[$parts[0]] = $parts[1];
            }
        }
        return $cookies;
    }

    /**
     * @return array
     */
    public static function getSessionHeaders()
    {
        $cookies = '';
        $session = self::getUserSession();
        foreach ($session as $key => $value) {
            $cookies .= "$key=$value; ";
        }
        $headers = [
            'cookie'      => $cookies,
            'referer'     => Endpoints::BASE_URL . '/',
            'x-csrftoken' => $session['csrftoken'],
        ];
        return $headers;
    }


}