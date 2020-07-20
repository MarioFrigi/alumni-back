<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PrivacitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('privacities')->insert([
            'id' => 2,
            'phone' => 0,
            'localization' => 0,
        ]);

        DB::table('privacities')->insert([
            'id' => 3,
            'phone' => 0,
            'localization' => 0,
        ]);

        DB::table('privacities')->insert([
            'id' => 4,
            'phone' =>  0,
            'localization' => 0,
        ]);

        DB::table('privacities')->insert([
            'id' => 5,
            'phone' => 0,
            'localization' => 0,
        ]);
    }
}
