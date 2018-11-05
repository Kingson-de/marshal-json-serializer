<?php

declare(strict_types = 1);

namespace KingsonDe\Marshal\Example\Mapper;

use KingsonDe\Marshal\AbstractMapper;
use KingsonDe\Marshal\Example\Model\User;

class UserMapper extends AbstractMapper {

    public function map(User $user) {
        return [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
        ];
    }
}
