<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Client Status object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/topics/gateway#client-status-object
 */
class ClientStatus extends AbstractObject implements ObjectInterface
{
    /**
     * @var string|null User active desktop application session status
     */
    protected $desktop;

    /**
     * @var string|null User active mobile application session status
     */
    protected $mobile;

    /**
     * @var string|null User active web application session status
     */
    protected $web;

    /**
     * @return string|null
     */
    public function getDesktop(): ?string
    {
        return $this->desktop;
    }

    /**
     * @return string|null
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * @return string|null
     */
    public function getWeb(): ?string
    {
        return $this->web;
    }
}
