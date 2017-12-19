<?php

declare(strict_types = 1);

namespace KingsonDe\Marshal;

use KingsonDe\Marshal\Data\CollectionCallable;
use PHPUnit\Framework\TestCase;

class MarshalJsonTest extends TestCase {

    public function testJsonSerialize() {
        $json = MarshalJson::serializeItemCallable([$this, 'mapUser'], $this->createUser());

        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/Fixtures/User.json', $json);
    }

    public function testJsonSerializeWithOptions() {
        MarshalJson::setEncodingOptions(JSON_HEX_TAG | JSON_HEX_AMP);

        $json = MarshalJson::serializeItemCallable([$this, 'mapUser'], $this->createUser());

        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/Fixtures/UserEscaped.json', $json);
    }

    public function testBuildDataStructureIsNull() {
        $json = MarshalJson::serializeItemCallable(function () {
            return null;
        });

        $this->assertJsonStringEqualsJsonString('{}', $json);
    }

    /**
     * @expectedException \KingsonDe\Marshal\Exception\JsonSerializeException
     */
    public function testSerializationFailed() {
        MarshalJson::serializeItemCallable(function () {
            return [
                'malformedJson' => "\xB1\x31",
            ];
        });
    }

    public function mapUser(\stdClass $user) {
        return [
            'id'        => $user->id,
            'score'     => $user->score,
            'email'     => $user->email,
            'null'      => null,
            'followers' => new CollectionCallable(function ($username) {
                return [
                    'username' => $username,
                ];
            }, $user->followers)
        ];
    }

    private function createUser() {
        $user            = new \stdClass();
        $user->id        = 123;
        $user->score     = 3.4;
        $user->email     = 'kingson@example.org';
        $user->followers = ['pfefferkuchenmann & <co>', 'lululu'];

        return $user;
    }
}
