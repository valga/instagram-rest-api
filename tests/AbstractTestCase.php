<?php

namespace InstagramRestApi\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use InstagramRestApi\Client as ApiClient;
use InstagramRestApi\Client\Http as HttpClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractTestCase extends TestCase
{
    /** @var LoggerInterface */
    protected $loggerMock;

    /** @var ApiClient */
    protected $apiClientMock;

    /** @var ClientInterface */
    protected $guzzleClientMock;

    /** @var HttpClient */
    protected $httpClientMock;

    /** @var ApiClient\Auth */
    protected $authMock;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->loggerMock = $this->getMockBuilder(NullLogger::class)
            ->setMethods(['info', 'error'])
            ->getMock();

        $this->guzzleClientMock = $this->getMockBuilder(GuzzleClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();

        $this->httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHttpClient', 'getApiClient'])
            ->getMock();

        $this->httpClientMock->expects($this->any())
            ->method('getHttpClient')
            ->willReturn($this->guzzleClientMock);

        $this->authMock = $this->getMockBuilder(ApiClient\Auth::class)
            ->disableOriginalConstructor()
            ->setMethods(['signUserRequest', 'signAppRequest'])
            ->getMock();

        $this->apiClientMock = $this->getMockBuilder(ApiClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['getJsonMapper', 'getAuth', 'getLogger', 'getHttpClient'])
            ->getMock();

        $this->apiClientMock->expects($this->any())
            ->method('getLogger')
            ->willReturn($this->loggerMock);

        $this->apiClientMock->expects($this->any())
            ->method('getHttpClient')
            ->willReturn($this->httpClientMock);

        $this->apiClientMock->expects($this->any())
            ->method('getAuth')
            ->willReturn($this->authMock);

        $jsonMapper = new \JsonMapper();
        $jsonMapper->bStrictNullTypes = false;
        $jsonMapper->bIgnoreVisibility = true;

        $this->apiClientMock->expects($this->any())
            ->method('getJsonMapper')
            ->willReturn($jsonMapper);

        $this->httpClientMock->expects($this->any())
            ->method('getApiClient')
            ->willReturn($this->apiClientMock);
    }

    /**
     * @param array       $headers
     * @param string|null $body
     */
    protected function setHttpResponse(array $headers = [], $body = null)
    {
        $response = new Response(200, $headers, $body);
        $this->guzzleClientMock->expects($this->once())
            ->method('request')
            ->willReturn($response);
    }
}
