<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Primer evento de burguer
        DB::table('comments')->insert([
            'description' => 'Me encanto la anvoguesa con doble de queso',
            'id_event' => '1',
            'id_user' => '5',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Me encanto la anvoguesa',
            'id_event' => '1',
            'id_user' => '4',
            'date' => date('Y-m-d H:i:s'),
        ]);

        //Concierto Natos y Waor
        DB::table('comments')->insert([
            'description' => 'Deseando verles',
            'id_event' => '2',
            'id_user' => '1',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Me encantan',
            'id_event' => '2',
            'id_user' => '2',
            'date' => date('Y-m-d H:i:s'),
        ]);

        //Anuncio Bitwork
        DB::table('comments')->insert([
            'description' => 'He oido buenas opiniones de la empresa.',
            'id_event' => '3',
            'id_user' => '5',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Me contactaron para las practicas, y no me dieron respuesta en semanas.',
            'id_event' => '3',
            'id_user' => '3',
            'date' => date('Y-m-d H:i:s'),
        ]);

        //Desarrollador iOS y Android
        DB::table('comments')->insert([
            'description' => 'Me encantan',
            'id_event' => '4',
            'id_user' => '2',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Me encantan',
            'id_event' => '4',
            'id_user' => '2',
            'date' => date('Y-m-d H:i:s'),
        ]);

        //Notas
        DB::table('comments')->insert([
            'description' => 'Voy a suspender fijo',
            'id_event' => '5',
            'id_user' => '5',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Asi me da tiempo a comer',
            'id_event' => '5',
            'id_user' => '3',
            'date' => date('Y-m-d H:i:s'),
        ]);

        //Info practicas
        DB::table('comments')->insert([
            'description' => 'Menos horitas para acabar jejejejej',
            'id_event' => '6',
            'id_user' => '5',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Ojala hubiese tenido yo la misma suerte.',
            'id_event' => '6',
            'id_user' => '2',
            'date' => date('Y-m-d H:i:s'),
        ]);

        //Trump
        DB::table('comments')->insert([
            'description' => 'Ese no sabe ni guiarse por su casa sin los guardias.',
            'id_event' => '7',
            'id_user' => '1',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Nos haria un gran favor.',
            'id_event' => '7',
            'id_user' => '4',
            'date' => date('Y-m-d H:i:s'),
        ]);

        //Juan Carlos
        DB::table('comments')->insert([
            'description' => 'Madre mia como se las gasta el tio este.',
            'id_event' => '8',
            'id_user' => '1',
            'date' => date('Y-m-d H:i:s'),
        ]);

        DB::table('comments')->insert([
            'description' => 'Abajo la monarquía.',
            'id_event' => '8',
            'id_user' => '5',
            'date' => date('Y-m-d H:i:s'),
        ]);
    }
}
