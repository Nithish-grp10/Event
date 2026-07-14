<?php

namespace App\Modules\Core\Enums;

enum RoleType: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Staff = 'staff';
    case User = 'user';

    public function label(): string
    {
        return match($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Staff => 'Staff',
            self::User => 'User',
        };
    }
}
