<?php

namespace Capimichi\Instagram\Request;

use Capimichi\Instagram\InstagramSession;
use Unirest\Request;

/**
 * Class AuthenticatedRequest
 * @package Capimichi\Instagram\Request
 */
class AuthenticatedRequest extends Request
{
    /**
     * @param string $url
     * @param array $headers
     * @param null $parameters
     * @param null $username
     * @param null $password
     * @return \Unirest\Response
     */
    public static function get($url, $headers = array(), $parameters = null, $username = null, $password = null)
    {
        $headers = array_merge($headers, InstagramSession::getSessionHeaders());
        return parent::get($url, $headers, $parameters, $username, $password);
    }

}