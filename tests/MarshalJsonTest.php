<?php

declare(strict_types = 1);

namespace KingsonDe\Marshal;

use KingsonDe\Marshal\Data\CollectionCallable;
use KingsonDe\Marshal\Data\FlexibleData;
use KingsonDe\Marshal\Example\Mapper\UserMapper;
use KingsonDe\Marshal\Example\Model\User;
use KingsonDe\Marshal\Example\ObjectMapper\UserIdMapper;
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

    /**
     * @expectedException \KingsonDe\Marshal\Exception\JsonSerializeException
     */
    public function testBuildDataStructureIsNull() {
        MarshalJson::serializeItemCallable(function () {
            return null;
        });
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

    public function testDeserializeMapperGeneratedJson() {
        $json = MarshalJson::serializeItem(new UserMapper(), new User(123, 'kingson@example.org'));

        $flexibleData = new FlexibleData(MarshalJson::deserializeJsonToData($json));

        $newJson = MarshalJson::serialize($flexibleData);

        $this->assertJsonStringEqualsJsonString($json, $newJson);
    }

    public function testDeserializeJsonFile() {
        $json = file_get_contents(__DIR__ . '/Fixtures/User.json');

        $flexibleData = new FlexibleData(MarshalJson::deserializeJsonToData($json));

        $newJson = MarshalJson::serialize($flexibleData);

        $this->assertJsonStringEqualsJsonString($json, $newJson);
    }

    public function testDeserializeToInt() {
        $json = file_get_contents(__DIR__ . '/Fixtures/User.json');

        $id = MarshalJson::deserializeJson($json, new UserIdMapper());

        $this->assertSame(123, $id);
    }

    public function testDeserializeWithCallable() {
        $json = file_get_contents(__DIR__ . '/Fixtures/User.json');

        $id = MarshalJson::deserializeJsonCallable($json, function (FlexibleData $flexibleData) {
            return $flexibleData['id'];
        });

        $this->assertSame(123, $id);
    }

    /**
     * @expectedException \KingsonDe\Marshal\Exception\JsonDeserializeException
     */
    public function testDeserializeInvalidJson() {
        MarshalJson::deserializeJsonToData('{not=valid}');
    }

    public function testModifyExistingJson() {
        $json = '{"name": "John Doe"}';

        $flexibleData = new FlexibleData(MarshalJson::deserializeJsonToData($json));
        $flexibleData['name'] = 'Jane Doe';

        $newJson = MarshalJson::serialize($flexibleData);

        $expectedJson = '{"name": "Jane Doe"}';

        $this->assertJsonStringEqualsJsonString($expectedJson, $newJson);
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
