<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{

    protected $signature = 'app:make_admin';

    protected $description = 'Create admin';

    public function handle()
    {
    }
}
