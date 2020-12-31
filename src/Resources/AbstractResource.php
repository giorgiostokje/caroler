<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Caroler;
use Caroler\Exceptions\InvalidArgumentException;
use Caroler\Objects\Message;
use GuzzleHttp\Exception\RequestException;

/**
 * Common Resource functionality
 *
 * @package Caroler\Resources
 */
abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var string|\Caroler\Objects\Message Context with which the Resource is approached
     */
    protected $context;

    /**
     * @var \Caroler\Caroler Application instance
     */
    protected $caroler;

    /**
     * @inheritDoc
     * @throws \Caroler\Exceptions\InvalidArgumentException
     */
    public function prepare($context, Caroler $caroler): ResourceInterface
    {
        if (!$context instanceof Message && !is_string($context)) {
            throw new InvalidArgumentException("Context must be either a Message Object or a string!");
        }

        $this->context = $context;
        $this->caroler = $caroler;

        return $this;
    }

    /**
     * Makes a (delayed) HTTP request against the Discord REST API.
     *
     * @param string $method
     * @param string $apiEndpoint
     * @param array|null $data
     * @param int $delay
     *
     * @return bool|array
     */
    private function makeHttpRequest(string $method, string $apiEndpoint, ?array $data = null, int $delay = 0)
    {
        sleep($delay);

        $route = static::API_RESOURCE . $apiEndpoint;
        $rateLimitBucket = $this->caroler->getRateLimitBucketByRoute($route);
        if (
            !is_null($rateLimitBucket)
            && $rateLimitBucket['remaining'] === 0
            && time() < $rateLimitBucket['reset']
        ) {
            return $this->makeHttpRequest($method, $apiEndpoint, $data, $rateLimitBucket['reset'] - time());
        }

        try {
            /** @var \GuzzleHttp\Psr7\Response $response */
            $response = $this->caroler->getHttpClient()->$method(
                $route,
                $method === 'post' || $method === 'put' ? ['json' => $data] : ['query' => $data]
            );

            if ($response->hasHeader('X-RateLimit-Bucket')) {
                $this->caroler->updateRateLimitBucket(
                    $response->getHeader('X-RateLimit-Bucket')[0],
                    $route,
                    (int) $response->getHeader('X-RateLimit-Remaining')[0] ?? null,
                    (int) $response->getHeader('X-RateLimit-Reset')[0] ?? null
                );
            }

            switch ($response->getStatusCode()) {
                case 204:
                    return true;
                default:
                    return json_decode((string) $response->getBody(), true);
            }
        } catch (RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 429) {
                $this->makeHttpRequest(
                    $method,
                    $apiEndpoint,
                    $data,
                    (int) ceil(json_decode((string) $e->getResponse()->getBody())->retry_after / 1000)
                );
            }
        }

        return false;
    }

    /**
     * Makes a HTTP GET request against the Discord REST API.
     *
     * @param string $apiEndpoint
     * @param array|null $query
     *
     * @return bool|array
     */
    protected function get(string $apiEndpoint, array $query = null)
    {
        return $this->makeHttpRequest('get', $apiEndpoint, $query);
    }

    /**
     * Makes a HTTP POST request against the Discord REST API.
     *
     * @param string $apiEndpoint
     * @param array $params
     *
     * @return bool|array
     */
    protected function post(string $apiEndpoint, array $params)
    {
        return $this->makeHttpRequest('post', $apiEndpoint, $params);
    }

    /**
     * Makes a HTTP PUT request against the Discord REST API.
     *
     * @param string $apiEndpoint
     * @param array $params
     *
     * @return bool|array
     */
    protected function put(string $apiEndpoint, array $params)
    {
        return $this->makeHttpRequest('put', $apiEndpoint, $params);
    }

    /**
     * Makes a HTTP DELETE request against the Discord REST API.
     *
     * @param  string  $apiEndpoint
     *
     * @return bool
     */
    protected function delete(string $apiEndpoint): bool
    {
        return $this->makeHttpRequest('delete', $apiEndpoint);
    }
}
