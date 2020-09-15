<?php

declare(strict_types=1);

namespace Caroler\Objects;

use Caroler\Traits\Arrayable;
use Caroler\Traits\Populatable;

/**
 * Common (Discord) object functionality
 *
 * @package Caroler\Objects
 */
abstract class AbstractObject implements ObjectInterface
{
    use Arrayable;
    use Populatable;

    /**
     * @inheritDoc
     */
    public function prepare(array $data): ObjectInterface
    {
        $this->populate($data);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function transform(array $data, string $object): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = (new $object())->prepare($value);
        }

        return $data;
    }
}
