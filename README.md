# Marshal JSON Serializer

![Marshal Serializer logo](https://raw.githubusercontent.com/Kingson-de/marshal-serializer/master/marshal.png "Marshal Serializer")

[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://github.com/Kingson-de/marshal-json-serializer/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/Kingson-de/marshal-json-serializer.svg?branch=master)](https://travis-ci.org/Kingson-de/marshal-json-serializer)
[![Code Coverage](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/?branch=master)

## Introduction

Marshal JSON is [serializing](https://en.wikipedia.org/wiki/Serialization) / [marshalling](https://en.wikipedia.org/wiki/Marshalling_(computer_science)) data structures to JSON. It is also deserializing / unmarshalling JSON back to the data structures.

## Installation

Easiest way to install the library is via composer:
```
composer require kingson-de/marshal-json-serializer
```

The following PHP versions are supported:
* PHP 7.0
* PHP 7.1
* PHP 7.2
* PHP 7.3

## Execute tests
Just run:
```
composer test
```

Or without code coverage:
```
composer quicktest
```

## Usage

### How to create Data Structures which can be serialized?

Please check the [Marshal Serializer README](https://github.com/Kingson-de/marshal-serializer/blob/master/README.md) for more information.

### How to use the Marshal JSON Serializer library?

The library provides several static methods to create your JSON data once you defined the data structures.

```php
<?php

use KingsonDe\Marshal\Data\Item;
use KingsonDe\Marshal\MarshalJson;

$json = MarshalJson::serialize(new Item($mapper, $model));
// or
$json = MarshalJson::serializeItem($mapper, $model);
// or
$json = MarshalJson::serializeItemCallable(function (User $user) {
    return [
        'username'  => $user->getUsername(),
        'email'     => $user->getEmail(),
        'birthday'  => $user->getBirthday()->format('Y-m-d'),
        'followers' => count($user->getFollowers()),
    ];
}, $user);
// or
$json = MarshalJson::serializeCollection($mapper, $modelCollection);
// or 
$json = MarshalJson::serializeCollectionCallable(function (User $user) {
    return [
        'username'  => $user->getUsername(),
        'email'     => $user->getEmail(),
        'birthday'  => $user->getBirthday()->format('Y-m-d'),
        'followers' => count($user->getFollowers()),
    ];
}, $userCollection);
```

### Deserializing / Unmarshalling

To transform JSON back to your structure use Marshal's deserialize functions.
You need a class extending the AbstractObjectMapper which will be passed to the deserializeJson function.
When using FlexibleData's get function it will throw an OutOfBoundsException if the key does not exist.
If an exception is not needed the find function can be used which will return a custom default value in that case.

```php
<?php

use KingsonDe\Marshal\AbstractObjectMapper;
use KingsonDe\Marshal\Data\FlexibleData;

class UserIdMapper extends AbstractObjectMapper {

    public function map(FlexibleData $flexibleData, ...$additionalData) {
        return $flexibleData->get('id');
    }
}
```

```php
<?php

use KingsonDe\Marshal\MarshalJson;

$json = '{"id": 123}';

$id = MarshalJson::deserializeJson($json, new UserIdMapper());
```

Another option would be to use the deserializeCallable function.

```php
<?php

use KingsonDe\Marshal\MarshalJson;

$id = MarshalJson::deserializeJsonCallable($json, function (FlexibleData $flexibleData) {
    return $flexibleData['id'];
});
```

### Modify existing JSON

An easy way to modify existing JSON is to use FlexibleData.
Here is an example:

```php
<?php

use KingsonDe\Marshal\Data\FlexibleData;
use KingsonDe\Marshal\MarshalJson;

$json = '{"name": "John Doe"}';

$flexibleData = new FlexibleData(MarshalJson::deserializeJsonToData($json));
$flexibleData['name'] = 'Jane Doe';

$modifiedJson = MarshalJson::serialize($flexibleData);
```

## License

This project is released under the terms of the [Apache 2.0 license](https://github.com/Kingson-de/marshal-json-serializer/blob/master/LICENSE).
