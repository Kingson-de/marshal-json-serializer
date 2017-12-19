<?php

declare(strict_types = 1);

namespace KingsonDe\Marshal;

use KingsonDe\Marshal\Data\DataStructure;
use KingsonDe\Marshal\Exception\JsonSerializeException;

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
    protected static $encodingOptions = 0;

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
        $data = static::buildDataStructure($dataStructure);

        if (null === $data) {
            return '{}';
        }

        $json = json_encode(
            static::buildDataStructure($dataStructure),
            static::$encodingOptions
        );

        if (false === $json) {
            throw new JsonSerializeException(json_last_error_msg());
        }

        return $json;
    }
}
