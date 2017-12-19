# Marshal JSON Serializer

[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://github.com/Kingson-de/marshal-json-serializer/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/Kingson-de/marshal-json-serializer.svg?branch=master)](https://travis-ci.org/Kingson-de/marshal-json-serializer)
[![Code Coverage](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Kingson-de/marshal-json-serializer/?branch=master)

## Introduction

Marshal is [serializing](https://en.wikipedia.org/wiki/Serialization) / [marshalling](https://en.wikipedia.org/wiki/Marshalling_(computer_science)) data structures to a format that can be used to build messages for transferring data through the wires.

Marshal JSON Serializer will directly serialize the data to JSON, it is built on top of the [Marshal Serializer](https://github.com/Kingson-de/marshal-serializer).

## Installation

Easiest way to install the library is via composer:
```
composer require kingson-de/marshal-json-serializer
```

The following PHP versions are supported:
* PHP 7.0
* PHP 7.1
* PHP 7.2

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

## License

This project is released under the terms of the [Apache 2.0 license](https://github.com/Kingson-de/marshal-json-serializer/blob/master/LICENSE).
