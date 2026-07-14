<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Users\Models\User;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'staff@rtih.test'],
            [
                'name' => 'RTIH Staff',
                'password' => bcrypt('changeme123'),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('staff');
    }
}
