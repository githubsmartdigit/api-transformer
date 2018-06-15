<?php

namespace Xooxx\Api\Mapping;

use ReflectionClass;
trait NullableTrait
{
    /**
     * Creates a null Value Object.
     *
     * @return object
     * @throws \ReflectionException
     */
    public static function null()
    {
        return (new ReflectionClass(get_called_class()))->newInstanceWithoutConstructor();
    }
    /**
     * @return bool
     */
    public function isNull()
    {
        return empty(array_filter(get_object_vars($this)));
    }
}