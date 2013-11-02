<?php
/**
 * This file is part of the Tmdb PHP API created by Michael Roterman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Tmdb
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2013, Michael Roterman
 * @version 0.0.1
 */
namespace Tmdb;

class ApiToken {
    private $apiToken = null;

    /**
     * Token bag
     *
     * @param $api_token
     */
    public function __construct($api_token = null)
    {
        $this->apiToken = $api_token;
    }

    /**
     * @param null $apiToken
     * @return $this
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    /**
     * @return null
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

}