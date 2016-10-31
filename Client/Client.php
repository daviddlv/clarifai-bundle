<?php

namespace ClarifaiBundle\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use Sainsburys\Guzzle\Oauth2\GrantType\RefreshToken;
use Sainsburys\Guzzle\Oauth2\GrantType\ClientCredentials;
use Sainsburys\Guzzle\Oauth2\Middleware\OAuthMiddleware;

class Client
{
    const CLARIFAI_API_BASE_URI = 'https://api.clarifai.com';

    const CLARIFAI_API_TOKEN_URI = '/v1/token';

    const CLARIFAI_API_PATTER_URI = '/v1/%s';

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var OAuthMiddleware
     */
    protected $middleware;

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var array
     */
    protected $clientConfig = array();

    /**
     * @var array
     */
    protected $authConfig = array();

    public function __construct(array $config, array $authConfig)
    {
        $this->config = $config;
        $this->clientConfig = array('base_uri' => Client::CLARIFAI_API_BASE_URI);
        $this->authConfig = array_merge($authConfig, array('token_url' => Client::CLARIFAI_API_TOKEN_URI));
    }

    /**
     * @return GuzzleClient
     */
    public function getClient()
    {
        if ($this->client) {
            return $this->client;
        }

        $handlerStack = HandlerStack::create();
        $this->clientConfig = array_merge($this->clientConfig, array(
            'handler'=> $handlerStack,
            'auth' => 'oauth2',
        ));
        $client = new GuzzleClient($this->clientConfig);

        $this->authConfig = array_merge($this->authConfig, array(
            'scope' => 'api_access',
        ));
        $token = new ClientCredentials($client, $this->authConfig);
        $refreshToken = new RefreshToken($client, $this->authConfig);
        $middleware = new OAuthMiddleware($client, $token, $refreshToken);

        $handlerStack->push($middleware->onBefore());
        $handlerStack->push($middleware->onFailure(5));

        $this->middleware = $middleware;

        return $this->client = $client;
    }

    /**
     * @return OAuthMiddleware
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * @param $method
     * @param $args
     * @return array
     */
    public function __call($method, $args)
    {
        if (!empty($args)) {
            $args = array(RequestOptions::QUERY => $args[0]);
            $args[RequestOptions::QUERY] = array_merge($this->config, $args[RequestOptions::QUERY]);
        } else {
            $args[RequestOptions::QUERY] = $this->config;
        }
        $response = $this->getClient()->get(sprintf(Client::CLARIFAI_API_PATTER_URI, str_replace('_', '/', $method)), $args);

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }
}