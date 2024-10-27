<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{

    protected $signature = 'app:make_admin';
    protected $description = 'Create admin';

    public function handle(): void
    {
        if (app()->isProduction()) return;

        $admin = User::query()->updateOrCreate(
            [
                'email' => 'admin@admin.com'
            ],
            [
                'name' => 'def_admin',
                'email' => 'admin@admin.com',
                'password' => 12345,
                'permissions' => json_encode([
                    'platform.index' => 1,
                    'platform.systems.roles' => 1,
                    'platform.systems.users' => 1,
                    'platform.systems.attachment' => 1
                ])
            ]
        );

        $this->info("Amin {$admin->name} was created");
    }
}
