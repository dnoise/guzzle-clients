<?php

namespace Guzzle\Twitter;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Twitter Client
 *
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class TwitterClient extends Client
{
    public static function factory($config = array())
    {
        $defaults = array(
            'base_url' => 'https://api.twitter.com/{version}/',
            'version' => '1.1',
        );
        $required = array('base_url', 'version');
        $config = Collection::fromConfig($config, $defaults, $required);

        $client = new self($config->get('base_url'), $config);

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/client.json');
        $client->setDescription($description);

        $client->addSubscriber(new OauthPlugin(array(
            'consumer_key'    => '',
            'consumer_secret' => '',
            'token'           => '',
            'token_secret'    => '',
        )));

        return $client;
    }
}
