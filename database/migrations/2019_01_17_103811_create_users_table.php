<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->integer('phone')->nullable();
            $table->string('username');
            $table->string('birthday')->nullable();
            $table->boolean('is_registered');
            $table->integer('id_rol')->unsigned();
            $table->foreign('id_rol')
                  ->references('id')->on('roles')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->integer('id_privacity')->unsigned();
            $table->foreign('id_privacity')
                  ->references('id')->on('privacities')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->double('lon')->nullable();
            $table->double('lat')->nullable();
            $table->timestamps();
        });

        $user = new App\Users();
        $user->password = Hash::make('admin');
        $user->email = 'prueba@cev.com';
        $user->username = 'Prueba';
        $user->is_registered = '1';
        $user->id_rol = '1';
        $user->id_privacity = 1;
        $user->name = 'Prueba';
        $user->photo = 'https://escuelaestech.es/wp-content/uploads/bfi_thumb/equipo15-blanco-y-negro-o97vhihst2l4rrmbwi5h7qzwv0pkfq7ukgbywdd5ho.jpg';
        $user->description = 'Señores mios, Mañana diia 15 de Mayo es San Isidro, festivo en Madrid. Aunque me opongo al 100% a que sea festivo para vosotros, vamos a posponer la clase al lunes 18 ;) Con lo cual, el lunes a las 17:00 vemos avances  y desarrollos Feliz puente a todos!';
        $user->phone = 722432594;
        $user->lon = -3.715500;
        $user->lat = 40.437562;
        $user->save();
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
