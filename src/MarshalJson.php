<?php

declare(strict_types = 1);

namespace KingsonDe\Marshal;

use KingsonDe\Marshal\Data\DataStructure;
use KingsonDe\Marshal\Data\FlexibleData;
use KingsonDe\Marshal\Exception\JsonDeserializeException;
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
     * @throws \KingsonDe\Marshal\Exception\JsonSerializeException
     */
    public static function serialize(DataStructure $dataStructure) {
        $data = static::buildDataStructure($dataStructure);

        if (null === $data) {
            throw new JsonSerializeException('No data structure.');
        }

        $json = json_encode(
            $data,
            static::$encodingOptions
        );

        if (false === $json) {
            throw new JsonSerializeException(json_last_error_msg());
        }

        return $json;
    }

    /**
     * @param string $json
     * @return array
     * @throws \KingsonDe\Marshal\Exception\JsonDeserializeException
     */
    public static function deserializeJsonToData(string $json): array {
        $data = json_decode($json, true);

        if (null === $data) {
            throw new JsonDeserializeException('JSON could not be deserialized.');
        }

        return $data;
    }

    /**
     * @param string $json
     * @param AbstractObjectMapper $mapper
     * @param mixed[] $additionalData
     * @return mixed
     */
    public static function deserializeJson(
        string $json,
        AbstractObjectMapper $mapper,
        ...$additionalData
    ) {
        return $mapper->map(
            new FlexibleData(static::deserializeJsonToData($json)),
            ...$additionalData
        );
    }

    /**
     * @param string $json
     * @param callable $mappingFunction
     * @param mixed[] $additionalData
     * @return mixed
     */
    public static function deserializeJsonCallable(
        string $json,
        callable $mappingFunction,
        ...$additionalData
    ) {
        return $mappingFunction(
            new FlexibleData(static::deserializeJsonToData($json)),
            ...$additionalData
        );
    }
}
