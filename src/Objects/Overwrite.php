<?php

declare(strict_types=1);

namespace Caroler\Objects;

/**
 * Overwrite object class
 *
 * @package Caroler\Objects
 * @see https://discord.com/developers/docs/resources/channel#overwrite-object
 */
class Overwrite extends AbstractObject implements ObjectInterface
{
    /**
     * @var string Role or user id
     */
    protected $id;

    /**
     * @var string Either "role" or "member"
     */
    protected $type;

    /**
     * @var int Legacy permission bit set
     */
    protected $allow;

    /**
     * @var string Permission bit set
     */
    protected $allowNew;

    /**
     * @var int Legacy permission bit set
     */
    protected $deny;

    /**
     * @var string Permission bit set
     */
    protected $denyNew;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getAllow(): int
    {
        return $this->allow;
    }

    /**
     * @return string
     */
    public function getAllowNew(): string
    {
        return $this->allowNew;
    }

    /**
     * @return int
     */
    public function getDeny(): int
    {
        return $this->deny;
    }

    /**
     * @return string
     */
    public function getDenyNew(): string
    {
        return $this->denyNew;
    }
}
