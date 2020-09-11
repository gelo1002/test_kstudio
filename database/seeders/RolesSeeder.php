<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('t_roles')->insert([
        ['name' => 'Administrador', 'key' => 'admin', 'description' => "Administrador"],
        ['name' => 'Moderador', 'key' => 'moderator', 'description' => "Pueden ver, validar y borrar información"],
        ['name' => 'Usuario de plataforma', 'key' => 'platform_user', 'description' => "Pueden ver, cargar y borrar información"],
      ]);

      $all = DB::table('t_roles')->get();

      foreach ($all as $key => $a) {
        $encrypt_id = encrypt($a->id);
        DB::table('t_roles')->where("id", $a->id)->update(['encrypt_id' => $encrypt_id]);
      }

    }
}