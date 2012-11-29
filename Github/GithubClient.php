<?php

namespace Guzzle\Github;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Github Client
 *
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class GithubClient extends Client
{
    public static function factory($config = array())
    {
        $defaults = array(
            // @TODO Auth should be through OAuth.
            'base_url' => 'https://{username}:{password}@api.github.com',
        );

        $required = array('username', 'password', 'base_url');
        $config = Collection::fromConfig($config, $defaults, $required);

        $client = new self($config->get('base_url'), $config);

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/client.json');
        $client->setDescription($description);

        return $client;
    }
}
