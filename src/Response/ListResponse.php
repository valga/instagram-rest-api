<?php

namespace InstagramRestApi\Response;

use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Request;
use InstagramRestApi\ResponseInterface;

class ListResponse extends EndpointResponse
{
    /**
     * @var Model\Pagination|null
     */
    protected $pagination;

    /**
     * @return Model\Pagination|null
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return bool
     */
    private function hasNextPage()
    {
        return $this->getPagination() !== null && $this->getPagination()->getNextUrl() !== null;
    }

    /**
     * @throws RequestException
     *
     * @return ResponseInterface|null
     */
    public function getNextPage()
    {
        if (!$this->hasNextPage()) {
            return null;
        }
        // We need to re-sign request, so let's split it.
        $url = substr($this->getPagination()->getNextUrl(), strlen(Request::API_ENDPOINT));
        $query = parse_url($url, PHP_URL_QUERY);
        // Strip old auth data.
        if ($query !== null) {
            parse_str($query, $query);
            unset($query['sig']);
            unset($query['access_token']);
        } else {
            $query = [];
        }
        // Create a new request.
        $request = $this->getClient()->request('GET', parse_url($url, PHP_URL_PATH));
        foreach ($query as $key => $value) {
            $request->addParam($key, $value);
        }
        /** @var ResponseInterface $response */
        $response = $request->getResponse(static::class);

        return $response;
    }
}
