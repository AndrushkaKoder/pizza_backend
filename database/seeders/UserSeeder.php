<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::factory(10)->create();

        $roles = include_once storage_path('seed/roles/roles.php');
        $admin = User::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make(12345)
        ]);
        $admin->roles()->create($roles['admin']);
    }

}
