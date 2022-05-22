<?php
declare(strict_types=1);

namespace tobias14\clearlag\utils;

final class ArrayUtils
{

    /**
     * @param object|string $objectOrClass
     * @param class-string[] $haystack
     * @return bool
     */
    public static function containsRelatedClassOf(object|string $objectOrClass, array $haystack): bool
    {
        foreach($haystack as $class) {
            if(is_a($objectOrClass, $class)) {
                return true;
            }
        }
        return false;
    }

}
