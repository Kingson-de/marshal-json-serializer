<?php

declare(strict_types = 1);

namespace KingsonDe\Marshal\Example\ObjectMapper;

use KingsonDe\Marshal\AbstractObjectMapper;
use KingsonDe\Marshal\Data\FlexibleData;

class UserIdMapper extends AbstractObjectMapper {

    public function map(FlexibleData $flexibleData, ...$additionalData) {
        return $flexibleData->get('id');
    }
}
