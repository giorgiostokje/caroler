<?php

declare(strict_types=1);

namespace Caroler\Resources;

use Caroler\Caroler;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use stdClass;

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
     */
    public function prepare($context, Caroler $caroler): ResourceInterface
    {
        $this->context = $context;
        $this->caroler = $caroler;

        return $this;
    }

    /**
     * Makes a HTTP request against the Discord REST API.
     *
     * @param string $method
     * @param string $apiEndpoint
     * @param array $data
     * @param int $delay
     *
     * @return \stdClass|null
     */
    private function makeHttpRequest(string $method, string $apiEndpoint, array $data, int $delay = 0): ?stdClass
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
            $response = $this->caroler->getHttpClient()->$method(
                $route,
                ['json' => $data]
            );

            if ($response->hasHeader('X-RateLimit-Bucket')) {
                $this->caroler->updateRateLimitBucket(
                    $response->getHeader('X-RateLimit-Bucket')[0],
                    $route,
                    (int) $response->getHeader('X-RateLimit-Remaining')[0] ?? null,
                    (int) $response->getHeader('X-RateLimit-Reset')[0] ?? null
                );
            }

            return json_decode((string) $response->getBody());
        } catch (RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 429) {
                $this->makeHttpRequest(
                    $method,
                    $apiEndpoint,
                    $data,
                    (int) ceil(json_decode((string) $e->getResponse()->getBody())->retry_after / 1000)
                );
            }

            // TODO: implement other HTTP errors
        }

        return null;
    }

    /**
     * Makes a HTTP GET request against the Discord REST API.
     *
     * @param string $apiEndpoint
     * @param array $data
     *
     * @return \stdClass|null
     */
    protected function get(string $apiEndpoint, array $data): ?stdClass
    {
        return $this->makeHttpRequest('get', $apiEndpoint, $data);
    }

    /**
     * Makes a HTTP POST request against the Discord REST API.
     *
     * @param string $apiEndpoint
     * @param array $data
     *
     * @return \stdClass|null
     */
    protected function post(string $apiEndpoint, array $data): ?stdClass
    {
        return $this->makeHttpRequest('post', $apiEndpoint, $data);
    }
}
