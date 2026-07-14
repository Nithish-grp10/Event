<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Users\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@rtih.test'],
            [
                'name' => 'RTIH Admin',
                'password' => bcrypt('changeme123'),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('admin');
    }
}
