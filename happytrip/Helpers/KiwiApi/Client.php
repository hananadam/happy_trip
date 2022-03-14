<?php

namespace Helpers\KiwiApi;

use App\Exceptions\KiwiException;
use Dingo\Api\Exception\ValidationHttpException;
use GuzzleHttp;

class Client
{
    const ENDPOINT = "https://tequila-api.kiwi.com";
    const KEY = "9Ftlraxi37JysnSTjYIrt9cxv33vQNiH";
    const MULTI_KEY = "xt5YCV3yEHA4yhR0sfg6PcOlE8-q5RZO";

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => self::ENDPOINT,
            'headers' => [
                'apikey' => self::KEY,
                'accept'    =>  'application/json'
            ]
        ]);
    }

    /**
     * @param $uri
     * @param array $query
     * @return mixed
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function get($uri, array $query = [])
    {
        try {
            $response = $this->client->request('GET', $uri, [
                'query' => $query
            ]);

            return json_decode($response->getBody()->getContents(), 1);
        } catch (GuzzleHttp\Exception\BadResponseException $badResponseException) {
            $errors = json_decode($badResponseException->getResponse()->getBody()->getContents(), true);
            if (isset($errors['errors'])) {
                $errors = $errors['errors'];
            }
            throw new ValidationHttpException($errors);
        }
    }

    /**
     * @param $uri
     * @param array $query
     * @return mixed
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws KiwiException
     */
    public function post($uri, array $query = [])
    {
        try {
            $response = $this->client->request('POST', $uri, [
                'json' => $query
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleHttp\Exception\BadResponseException $badResponseException) {
            $errors = json_decode($badResponseException->getResponse()->getBody()->getContents(), true);
//            $errors = json_decode($badResponseException->getResponse()->getBody()->getContents(), true)['errors'];
            throw new ValidationHttpException($errors);
        }
    }

    /**
     * @param $uri
     * @param array $query
     * @return mixed
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws KiwiException
     */
    public function postWithKey($uri, array $query = [], $key)
    {
        try {
            $response = $this->client->request('POST', $uri, [
                'headers' => [
                    'apikey' => $key,
                    'accept'    =>  'application/json'
                ],
                'json' => $query
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleHttp\Exception\BadResponseException $badResponseException) {
            $errors = json_decode($badResponseException->getResponse()->getBody()->getContents(), true);
            if (isset($errors['errors'])) {
                $errors = $errors['errors'];
            } elseif ($errors['status'] == 'error') {
                $errors = $errors['msg'];
            }
            throw new ValidationHttpException($errors);
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^(get)(?<method>[a-zA-Z]+)$/', $name, $matches))
        {
            $classMap = 'Helpers\\KiwiApi\\Services\\'.$matches['method'];
            return new $classMap($this);
        }
    }
}
