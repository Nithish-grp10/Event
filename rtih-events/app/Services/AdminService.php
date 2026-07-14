<?php

namespace App\Services;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    /**
     * Create a new admin user and assign a role.
     */
    public function createAdmin(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        return $user;
    }

    /**
     * Update an existing admin's role.
     */
    public function updateAdminRole(User $admin, string $role): User
    {
        $admin->syncRoles([$role]);
        return $admin;
    }
}
