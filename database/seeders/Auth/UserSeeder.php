<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: UserSeeder.php
 */
namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        User::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => '1234rewq',
            'email_verified_at' => now(),
            'active' => true,
        ]);

        if (app()->environment(['local', 'testing'])) {
            User::create([
                'type' => User::TYPE_USER,
                'name' => 'Test User',
                'email' => 'user@user.com',
                'password' => '1234rewq',
                'email_verified_at' => now(),
                'active' => true,
            ]);
        }

        $this->enableForeignKeys();
    }
}
