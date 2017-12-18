<?php

declare(strict_types = 1);

namespace KingsonDe\Marshal;

use KingsonDe\Marshal\Data\DataStructure;

/**
 * @method static string serializeItem(AbstractMapper $mapper, ...$data)
 * @method static string serializeItemCallable(callable $mappingFunction, ...$data)
 * @method static string serializeCollection(AbstractMapper $mapper, ...$data)
 * @method static string serializeCollectionCallable(callable $mappingFunction, ...$data)
 */
class MarshalJson extends Marshal {

    /**
     * @var int
     */
    private static $encodingOptions = 0;

    /**
     * @param int $encodingOptions
     * @link http://php.net/manual/en/function.json-encode.php
     */
    public static function setEncodingOptions(int $encodingOptions) {
        static::$encodingOptions = $encodingOptions;
    }

    /**
     * @param DataStructure $dataStructure
     * @return string
     */
    public static function serialize(DataStructure $dataStructure) {
        return json_encode(
            static::buildDataStructure($dataStructure),
            static::$encodingOptions
        );
    }
}
