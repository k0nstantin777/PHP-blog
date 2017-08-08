<?php

namespace core;

/**
 * Description of ObjectContainer
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ObjectContainer
{
    private static $objects = [];

    public static function get($object)
    {
        if (!in_array($object, self::$objects)) {
            self::init($object);
        }

        return self::$objects[array_search($object, self::$objects)];
    }

    private static function init($object)
    {
        return self::$objects[] = $object;
    }
}
