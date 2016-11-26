<?php

namespace ClarifaiBundle\Client;

use ClarifaiBundle\Clarifai\ArrayableInterface;
use ClarifaiBundle\Client\GrantType\ClientCredentials;
use ClarifaiBundle\Client\GrantType\RefreshToken;
use ClarifaiBundle\Client\Middleware\OAuthMiddleware;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;

class Client
{
    const CLARIFAI_API_BASE_URI = 'https://api.clarifai.com';

    const CLARIFAI_API_TOKEN_URI = '/v2/token';

    const CLARIFAI_API_PATTER_URI = '/v2/%s';

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $clientConfig = array();

    /**
     * @var array
     */
    protected $authConfig = array();

    public function __construct(array $authConfig)
    {
        $this->clientConfig = array(
            'base_uri' => Client::CLARIFAI_API_BASE_URI,
        );
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
            'scope' => 'api_access_write api_access api_access_read',
        ));
        $token = new ClientCredentials($client, $this->authConfig);
        $refreshToken = new RefreshToken($client, $this->authConfig);
        $middleware = new OAuthMiddleware($client, $token, $refreshToken);

        $handlerStack->push($middleware->onBefore());
        $handlerStack->push($middleware->onFailure(5));

        return $this->client = $client;
    }

    /**
     * @param $method
     * @param $args
     * @return array
     */
    public function __call($method, $args)
    {
        $requestMethod = 'POST';
        $data = array();

        if (!empty($args)) {
            if (isset($args[1])) {
                $requestMethod = $args[1];
            }

            $data = $args[0];
        }

        if ($data instanceof ArrayableInterface) {
            $data = $data->toArray();
        }

        $requestOption = $this->getRequestOption($requestMethod);
        $data = array($requestOption => $data);

        if ($requestOption === RequestOptions::BODY) {
            if (!empty($data[$requestOption])) {
                $data[$requestOption] = \GuzzleHttp\json_encode($data[$requestOption]);
            } else {
                unset($data[$requestOption]);
            }

            $data[RequestOptions::HEADERS] = array(
                'Content-Type' => 'application/json',
            );
        }

        $response = $this->getClient()->request($requestMethod, sprintf(Client::CLARIFAI_API_PATTER_URI, str_replace('_', '/', $method)), $data);

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

    private function getRequestOption($requestMethod)
    {
        switch (strtoupper($requestMethod))
        {
            case 'GET':
                $requestOption = RequestOptions::QUERY;
                break;
            case 'POST':
            default:
                $requestOption = RequestOptions::BODY;
        }

        return $requestOption;
    }
}