<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SocialNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('c_social_network')->insert([
          ['name' => 'facebook']
      ]);

      $all = DB::table('c_social_network')->get();

      foreach ($all as $key => $a) {
        $encrypt_id = encrypt($a->id);
        DB::table('c_social_network')->where("id", $a->id)->update(['encrypt_id' => $encrypt_id]);
      }

    }
}
