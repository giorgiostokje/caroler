<?php

declare(strict_types=1);

namespace Caroler\Traits;

use stdClass;

/**
 * Helper for classes that can be populated with pre-existing data.
 *
 * @package Caroler\Traits
 */
trait Populatable
{
    /**
     * Iterates the given object sets the corresponding class property.
     *
     * Only existing class properties will be set. Underscores are removed and the next character will be changed to
     * uppercase. The classes making use of this method must have their property names written in camelCase.
     *
     * @param \stdClass|null $data
     */
    public function populate(?stdClass $data): void
    {
        if (isset($data)) {
            foreach ($data as $key => $val) {
                $key = str_replace('_', '', lcfirst(ucwords($key, '_')));

                if (property_exists($this, $key)) {
                    $this->{$key} = $val;
                }
            }
        }
    }
}
