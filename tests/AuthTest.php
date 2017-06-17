<?php

namespace InstagramRestApi\Tests;

use InstagramRestApi\Client\Auth;
use InstagramRestApi\Request;

class AuthTest extends AbstractTestCase
{
    /**
     * @covers \Auth::signAppRequest
     */
    public function testAppSignature()
    {
        $request = new Request($this->apiClientMock, 'GET', '/');

        $auth = new Auth([
            'clientId'     => 'bb422fd843a5661f1eacdb9c2325a10e',
            'clientSecret' => '6dc1787668c64c939929c17683d7cb74',
        ]);
        $auth->signAppRequest($request);

        $params = $request->getParams();
        $this->assertEquals(2, count($params));
        $this->assertArrayHasKey('client_id', $params);
        $this->assertEquals('bb422fd843a5661f1eacdb9c2325a10e', $params['client_id']);
        $this->assertArrayHasKey('client_secret', $params);
        $this->assertEquals('6dc1787668c64c939929c17683d7cb74', $params['client_secret']);
    }

    /**
     * @covers \Auth::signUserRequest
     */
    public function testUserSignatureWithoutAccessToken()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Access token is missing.');

        $request = new Request($this->apiClientMock, 'GET', '/');

        $auth = new Auth([
            'clientId'           => 'bb422fd843a5661f1eacdb9c2325a10e',
            'clientSecret'       => '6dc1787668c64c939929c17683d7cb74',
        ]);
        $auth->signUserRequest($request);
    }

    /**
     * @covers \Auth::signUserRequest
     */
    public function testUserSignatureWithoutEnforcing()
    {
        $request = new Request($this->apiClientMock, 'GET', '/');

        $auth = new Auth([
            'clientId'              => 'bb422fd843a5661f1eacdb9c2325a10e',
            'clientSecret'          => '6dc1787668c64c939929c17683d7cb74',
            'accessToken'           => 'fb2e77d.47a0479900504cb3ab4a1f626d174d2d',
            'enforceSignedRequests' => false,
        ]);
        $auth->signUserRequest($request);

        $params = $request->getParams();
        $this->assertEquals(1, count($params));
        $this->assertArrayHasKey('access_token', $params);
        $this->assertEquals('fb2e77d.47a0479900504cb3ab4a1f626d174d2d', $params['access_token']);
    }

    /**
     * @covers \Auth::secureRequest
     */
    public function testUserSignatureWithEnforcing()
    {
        $request = new Request($this->apiClientMock, 'GET', '/users/self');

        $auth = new Auth([
            'clientId'              => 'bb422fd843a5661f1eacdb9c2325a10e',
            'clientSecret'          => '6dc1787668c64c939929c17683d7cb74',
            'accessToken'           => 'fb2e77d.47a0479900504cb3ab4a1f626d174d2d',
            'enforceSignedRequests' => true,
        ]);
        $auth->signUserRequest($request);

        $params = $request->getParams();
        $this->assertEquals(2, count($params));
        $this->assertArrayHasKey('access_token', $params);
        $this->assertEquals('fb2e77d.47a0479900504cb3ab4a1f626d174d2d', $params['access_token']);
        $this->assertArrayHasKey('sig', $params);
        $this->assertEquals('cbf5a1f41db44412506cb6563a3218b50f45a710c7a8a65a3e9b18315bb338bf', $params['sig']);
    }

    /**
     * @covers \Auth::secureRequest
     */
    public function testUserSignatureWithEnforcingAndParams()
    {
        $request = new Request($this->apiClientMock, 'GET', '/media/657988443280050001_25025320');
        $request->addParam('count', 10);

        $auth = new Auth([
            'clientId'              => 'bb422fd843a5661f1eacdb9c2325a10e',
            'clientSecret'          => '6dc1787668c64c939929c17683d7cb74',
            'accessToken'           => 'fb2e77d.47a0479900504cb3ab4a1f626d174d2d',
            'enforceSignedRequests' => true,
        ]);
        $auth->signUserRequest($request);

        $params = $request->getParams();
        $this->assertEquals(3, count($params));
        $this->assertArrayHasKey('access_token', $params);
        $this->assertEquals('fb2e77d.47a0479900504cb3ab4a1f626d174d2d', $params['access_token']);
        $this->assertArrayHasKey('sig', $params);
        $this->assertEquals('260634b241a6cfef5e4644c205fb30246ff637591142781b86e2075faf1b163a', $params['sig']);
    }
}
