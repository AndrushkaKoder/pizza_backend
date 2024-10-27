<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = include_once storage_path('seed/roles/roles.php');
        foreach ($roles as $role) {
            Role::query()->create($role);
        }

    }

}
