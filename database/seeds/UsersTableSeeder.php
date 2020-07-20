<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin
        DB::table('users')->insert([
            'email' => 'admin@cev.com',
            'password' => Hash::make('admin'),
            'is_registered' => '1',
            'id_rol' => '1',
            'id_privacity' => 2,
            'photo' => 'https://www.trecebits.com/wp-content/uploads/2014/12/mario_bros.jpg',
            'name' => 'Mario Friginal',
            'username' => 'MarioFrigi',
        ]);

        //Coordinador
        DB::table('users')->insert([
            'email' => 'coordinador@cev.com',
            'password' => Hash::make('coordinador'),
            'is_registered' => '1',
            'id_rol' => '2',
            'id_privacity' => 3,
            'photo' => 'https://www.lecturas.com/medio/2020/03/10/alberto-isla_f9857c83_875x540.jpg',
            'name' => 'Alberto López',
            'username' => 'Alberti',
        ]);

        //Profesor
        DB::table('users')->insert([
            'email' => 'profe@cev.com',
            'password' => Hash::make('profe'),
            'is_registered' => '1',
            'id_rol' => '3',
            'id_privacity' => 4,
            'photo' => 'https://ocio.levante-emv.com/img_contenido/noticias/2015/01/382424/matias_prats.jpg',
            'name' => 'Matias Prats',
            'username' => 'erMatias',
        ]);

        //Alumno
        DB::table('users')->insert([
            'email' => 'alumno@cev.com',
            'password' => Hash::make('alumno'),
            'is_registered' => '1',
            'id_rol' => '4',
            'id_privacity' => 5,
            'photo' => 'https://www.madrid.uibs.org/images/UIBS_undergraduate_bachelor_07.jpg',
            'name' => 'Elber Galarga',
            'username' => 'Elbersito',
        ]);
    }
}
