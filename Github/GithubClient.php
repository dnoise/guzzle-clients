<?php

namespace Rvdv\Guzzle\Github;

use Guzzle\Common\Collection;
use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
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
            'base_url' => 'https://api.github.com',
        );

        $required = array('base_url');
        $config = Collection::fromConfig($config, $defaults, $required);

        $client = new self($config->get('base_url'), $config);

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
        $request = parent::createRequest($method, $uri, $headers, $body);

        $config = $this->getConfig();

        // Add curl authentication to the request when username and password is set.
        if ($config->hasKey('username') && $config->hasKey('password')) {
            $authPlugin = new CurlAuthPlugin($config->get('username'), $config->get('password'));
            $this->addSubscriber($authPlugin);
        }
        // Add client authentication to the query string when the id and secret is set.
        elseif ($config->hasKey('client_id') && $config->hasKey('client_secret')) {
            $request->getQuery()
                ->set('client_id', $config->get('client_id'))
                ->set('client_secret', $config->get('client_secret'))
            ;
        }

        return $request;
    }
}
