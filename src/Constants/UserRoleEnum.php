<?php

namespace App\Constants;

enum UserRoleEnum : string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public static function getChoices(): array
    {
        return [
            'ROLE_USER' => self::ROLE_USER,
            'ROLE_ADMIN' => self::ROLE_ADMIN,
            'ROLE_SUPER_ADMIN' => self::ROLE_SUPER_ADMIN,
        ];
    }

    public static function getFormChoices(): array
    {
        return [
            [
            'id' => 1,
            'role' => self::ROLE_USER,
            ],
            [
                'id' => 2,
                'role' => self::ROLE_ADMIN,
            ],
            [
                'id' => 3,
                'role' => self::ROLE_SUPER_ADMIN,
            ],
        ];
    }
}