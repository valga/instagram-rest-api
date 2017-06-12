<?php

namespace InstagramRestApi\Client;

use GuzzleHttp\Client as GuzzleClient;
use InstagramRestApi\Client as ApiClient;
use InstagramRestApi\Exception\NetworkException;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

/**
 * Wrapper for underlying HTTP Client.
 *
 * @see http://guzzlephp.org/
 */
class Http
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @var ApiClient
     */
    private $apiClient;

    /**
     * Constructor.
     *
     * @param ApiClient         $apiClient
     * @param GuzzleClient|null $guzzleClient
     */
    public function __construct(ApiClient $apiClient, GuzzleClient $guzzleClient = null)
    {
        $this->apiClient = $apiClient;

        if ($guzzleClient === null) {
            $this->guzzleClient = new GuzzleClient([
                'allow_redirects' => false,
                'connect_timeout' => 5.0,
                'timeout'         => 10.0,
            ]);
        } else {
            $this->guzzleClient = $guzzleClient;
        }
    }

    /**
     * @return GuzzleClient
     */
    protected function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * @return ApiClient
     */
    protected function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Sends a request and returns its response.
     *
     * @param string      $method
     * @param string      $url
     * @param null|string $body
     *
     * @throws NetworkException
     *
     * @return HttpResponseInterface
     */
    public function request($method, $url, $body = null)
    {
        $options = [
            'decode_content' => true,
            'http_errors'    => false,
            'headers'        => [
                'Accept'          => 'application/json',
                'Accept-Encoding' => 'gzip,deflate',
                'Connection'      => 'keep-alive',
                'User-Agent'      => 'Instagram API PHP Client',
            ],
        ];
        if ($body !== null) {
            $options['headers']['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
            $options['body'] = $body;
        }
        try {
            $response = $this->getGuzzleClient()->request($method, $url, $options);
            // Log successful request with its response.
            $message = sprintf('%s %s %d %s', $method, $url, $response->getStatusCode(), $response->getReasonPhrase());
            $context = [
                'request'  => $options,
                'response' => [
                    'headers' => $response->getHeaders(),
                    'body'    => (string) $response->getBody(),
                ],
            ];
            $this->getApiClient()->getLogger()->info($message, $context);
        } catch (\Exception $e) {
            // Log failed request.
            $context = array_merge([
                'method' => $method,
                'url'    => $url,
            ], $options);
            $this->getApiClient()->getLogger()->error($e->getMessage(), $context);
            throw new NetworkException($e);
        }

        return $response;
    }
}
