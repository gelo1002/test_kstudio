<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \date('Y-m-d H:i:s');

        $admin = User::create([
            'first_name'        => 'Admin',
            'last_name'         => 'Admin',
            'nickname'          => 'gelo1002',
            'email'             => 'admin1@test.kokonut.com',
            'email_hash'        => md5('admin1@test.kokonut.com'),
            'password'          => bcrypt("K0K0nu72@2@"),
            'role_id'           => 1,
            'active'            => 1,
            'avatar'            => NULL,
            'status'            => 1,
            'email_verified_at' => $now,
            'created_at'        => $now,
        ]);
        
        $admin->encrypt_id = encrypt($admin->id);
        $admin->save();

        $moderator = User::create([
            'first_name'        => 'Moderador',
            'last_name'         => 'Moderador',
            'nickname'          => 'gelo1002',
            'email'             => 'moderador1@test.kokonut.com',
            'email_hash'        => md5('moderador1@test.kokonut.com'),
            'password'          => bcrypt("K0K0nu72@2@"),
            'role_id'           => 2,
            'active'            => 1,
            'avatar'            => NULL,
            'status'            => 1,
            'email_verified_at' => $now,
            'created_at'        => $now,
        ]);

        $moderator->encrypt_id = encrypt($moderator->id);
        $moderator->save();
    }
}
