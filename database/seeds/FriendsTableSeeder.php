<?php

use Carbon\Traits\Timestamp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Usuario prueba
        DB::table('friends')->insert([
            'id_user_send' => 1,
            'id_user_receive' => 2,
            'state' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('friends')->insert([
            'id_user_send' => 1,
            'id_user_receive' => 3,
            'state' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('friends')->insert([
            'id_user_send' => 1,
            'id_user_receive' => 4,
            'state' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('friends')->insert([
            'id_user_send' => 1,
            'id_user_receive' => 5,
            'state' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // //Usuario admin
        // DB::table('friends')->insert([
        //     'id_user_send' => 2,
        //     'id_user_receive' => 1,
        //     'state' => 1,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 2,
        //     'id_user_receive' => 3,
        //     'state' => 0,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 2,
        //     'id_user_receive' => 4,
        //     'state' => 1,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 2,
        //     'id_user_receive' => 5,
        //     'state' => 0,
        // ]);

        // //Usuario coordinador
        // DB::table('friends')->insert([
        //     'id_user_send' => 3,
        //     'id_user_receive' => 1,
        //     'state' => 1,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 3,
        //     'id_user_receive' => 2,
        //     'state' => 0,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 3,
        //     'id_user_receive' => 4,
        //     'state' => 1,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 3,
        //     'id_user_receive' => 5,
        //     'state' => 0,
        // ]);

        // //Usuario profesor
        // DB::table('friends')->insert([
        //     'id_user_send' => 4,
        //     'id_user_receive' => 1,
        //     'state' => 1,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 4,
        //     'id_user_receive' => 2,
        //     'state' => 0,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 4,
        //     'id_user_receive' => 3,
        //     'state' => 1,
        // ]);

        // DB::table('friends')->insert([
        //     'id_user_send' => 4,
        //     'id_user_receive' => 5,
        //     'state' => 0,
        // ]);
    }
}
