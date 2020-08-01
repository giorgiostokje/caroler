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
     *
     * @return \stdClass|null
     */
    private function makeHttpRequest(string $method, string $apiEndpoint, array $data): ?stdClass
    {
        try {
            return json_decode((string) $this->caroler->getHttpClient()->$method(
                static::API_RESOURCE . $apiEndpoint,
                ['json' => $data]
            )->getBody());
        } catch (RequestException $e) {
            $this->caroler->write("Failed to send message: " . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $this->caroler->write("Discord responded with: " . Psr7\str($e->getResponse()));
            }
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
