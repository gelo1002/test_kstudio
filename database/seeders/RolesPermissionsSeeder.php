<?php

namespace Database\Seeders;
use DB;

use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_roles_permissions')->insert([
            //admin role
            ['role_id' => 1, 'permission_id'=> 1],//view-my-profile
            ['role_id' => 1, 'permission_id'=> 2],//view-my-files
            ['role_id' => 1, 'permission_id'=> 3],//files-view
            ['role_id' => 1, 'permission_id'=> 4],//files-delete
            ['role_id' => 1, 'permission_id'=> 5],//files-destroy
            ['role_id' => 1, 'permission_id'=> 6],//users-view
            ['role_id' => 1, 'permission_id'=> 7],//users-create
            ['role_id' => 1, 'permission_id'=> 8],//users-edit
            ['role_id' => 1, 'permission_id'=> 9],//users-delete
            ['role_id' => 1, 'permission_id'=> 10],//users-destroy

            //admin role
            ['role_id' => 2, 'permission_id'=> 1],//view-my-profile
            ['role_id' => 2, 'permission_id'=> 3],//files-view
            ['role_id' => 2, 'permission_id'=> 4],//files-delete
            ['role_id' => 2, 'permission_id'=> 5],//files-destroy
            ['role_id' => 2, 'permission_id'=> 6],//users-view

            //platform_user role
            ['role_id' => 3, 'permission_id'=> 1],//view-my-profile
            ['role_id' => 3, 'permission_id'=> 2],//view-my-files
        ]);
    }
}
