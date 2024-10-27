<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::factory(10)->create();

        $admin = User::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 12345,
            'permissions' => [
                'platform.index' => 1,
                'platform.systems.roles' => 1,
                'platform.systems.users' => 1,
                'platform.systems.attachment' => 1
            ]
        ]);
        $admin->roles()->sync(Role::query()->where('slug', 'admin')->first()->id);
    }

}
