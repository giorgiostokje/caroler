<?php

declare(strict_types=1);

namespace Caroler\Traits;

use ReflectionClass;

/**
 * Helper for classes that need their (private) properties retrieved as an array.
 *
 * @package Caroler\Traits
 */
trait Arrayable
{
    /**
     * Returns all non-null object properties as an array.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        $reflection = new ReflectionClass(get_called_class());
        $properties = $reflection->getProperties();
        $array = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            is_null($property->getValue($this))
                ?: $array[$property->getName()] = $property->getValue($this);
        }

        return $array;
    }
}
