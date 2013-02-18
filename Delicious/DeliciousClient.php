<?php

namespace Guzzle\Delicious;

use Guzzle\Common\Collection;
use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Delicious Client
 *
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class DeliciousClient extends Client
{
    public static function factory($config = array())
    {
        $defaults = array(
            'base_url' => 'https://api.delicious.com/v1/',
        );
        $required = array('base_url');

        $config = Collection::fromConfig($config, $defaults, $required);
        $client = new static($config->get('base_url'), $config);

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/client.json');
        $client->setDescription($description);

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null)
    {
        $config = $this->getConfig();

        // Add curl authentication to the request when username and password is set.
        if ($config->hasKey('username') && $config->hasKey('password')) {
            $authPlugin = new CurlAuthPlugin($config->get('username'), $config->get('password'));
            $this->addSubscriber($authPlugin);
        }

        return parent::createRequest($method, $uri, $headers, $body);
    }
}
