<?php

namespace Guzzle\Loggly;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Loggly Client
 *
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class LogglyClient extends Client
{
    public static function factory($config = array())
    {
        $defaults = array(
            'base_url' => 'https://logs.loggly.com',
        );

        $required = array('base_url');
        $config = Collection::fromConfig($config, $defaults, $required);

        $client = new self($config->get('base_url'), $config);

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/client.json');
        $client->setDescription($description);

        return $client;
    }
}
