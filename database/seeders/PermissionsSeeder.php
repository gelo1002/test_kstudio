<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_permissions')->insert([
            //===================== GENERAL =========================
            ['key' =>'view-my-profile', 'name' => 'Ver mi perfil'],
            ['key' =>'view-my-files', 'name' => 'Ver mis Archivos'],

            //===================== FILES ==========================
            ['key' =>'files-view', 'name' => 'Ver Archivos'],
            ['key' =>'files-delete', 'name' => 'Eliminar Archivo'],
            ['key' =>'files-destroy', 'name' => 'Destruir Archivo'],

            //===================== USER ==========================
            ['key' =>'users-view', 'name' => 'Ver Usuarios'],
            ['key' =>'users-create', 'name' => 'Crear Usuarios'],
            ['key' =>'users-edit', 'name' => 'Editar Usuarios'],
            ['key' =>'users-delete', 'name' => 'Eliminar Usuarios'],
            ['key' =>'users-destroy', 'name' => 'Destruir Usuarios'],

        ]);

        $all = DB::table('t_permissions')->get();

        foreach ($all as $key => $a) {
            $encrypt_id = encrypt($a->id);
            DB::table('t_permissions')->where("id", $a->id)->update(['encrypt_id' => $encrypt_id]);
        }
    }
}
