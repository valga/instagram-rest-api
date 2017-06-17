<?php

namespace InstagramRestApi\Tests;

use InstagramRestApi\Request;
use InstagramRestApi\Response\EndpointResponse;

class ParserTest extends AbstractTestCase
{
    public function testMapper()
    {
        $this->setHttpResponse([], '{"meta":{"code":200},"data":null}');

        $request = new Request($this->apiClientMock, 'GET', '/');
        /** @var EndpointResponse $response */
        $response = $request->getResponse(EndpointResponse::class);

        $this->assertInstanceOf(EndpointResponse::class, $response);
        $this->assertNull($response->getData());
        $this->assertNotNull($response->getMeta());
        $this->assertEquals(200, $response->getMeta()->getCode());
    }
}
