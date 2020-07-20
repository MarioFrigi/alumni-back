<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Eventos
        DB::table('events')->insert([
            'title' => 'Comida Burguer King',
            'description' => 'La cadena repartirá, desde las 19.54hs hasta las 20.20hs, un total de 100 menús Whopper® como premio para los 100 primeros usuarios que publiquen en su perfil de Instagram usando uno de los “Filter of the Whopper®”.',
            'id_type' => '1',
            'id_user' => '1',
            'url' => 'https://www.burgerking.es/home',
            'image' => 'https://www.reasonwhy.es/sites/default/files/impossible-whopper-burger-king.jpg',
            'lat' => '39.469893',
            'lon' => '-6.379268',
        ]);

        DB::table('events')->insert([
            'title' => 'Concierto de Natos y Waor',
            'description' => 'Dos de los raperos más influyentes y respetados dentro del panorama de la música urbana española. No han necesitado firmar con una discográfica para saborear el éxito, ni lanzar un solo single que llegue a todo el mundo.',
            'id_type' => '1',
            'id_user' => '1',
            'url' => 'https://natosywaor.com/',
            'image' => 'https://natosywaor.com/wp-content/uploads/sites/4/2017/10/home-natos-y-waor-542x339.jpg',
            'lat' => '40.386045',
            'lon' => '-3.738547',
        ]);

        //Ofertas de trabajo
        DB::table('events')->insert([
            'title' => 'Programador Back-End PHP Bitwork',
            'description' => 'Somos Bitwork una empresa internacional dedicada a transformar el conocimiento en soluciones de negocio rentables y sostenibles para nuestros clientes.',
            'id_type' => '2',
            'id_user' => '3',
            'url' => 'https://www.bitwork.es/',
            'image' => 'https://s3.amazonaws.com/wordpress-production/wp-content/uploads/sites/19/2016/09/practicas-para-ser-un-buen-programador.jpg',
            'lat' => '40.486060',
            'lon' => '-3.660787',
        ]);

        DB::table('events')->insert([
            'title' => 'Desarrollador/a Aplicaciones Móviles Android e iOS',
            'description' => 'En Vector ITC Group buscamos un Desarrollador Android con conocimientos en iOS y .Net, para una importante proyecto ubicado en Boadilla del Monte.',
            'id_type' => '2',
            'id_user' => '3',
            'url' => 'https://www.vectoritcgroup.com/',
            'image' => 'https://www.softzone.es/app/uploads-softzone.es/2020/03/Programadores.jpg',
            'lat' => '40.463058',
            'lon' => '-3.811293',
        ]);

        //Notificaciones
        DB::table('events')->insert([
            'title' => 'Las notas finales serán publicadas el dia 20 de Junio.',
            'description' => 'La cadena repartirá, desde las 19.54hs hasta las 20.20hs, un total de 100 menús Whopper® como premio para los 100 primeros usuarios que publiquen en su perfil de Instagram usando uno de los “Filter of the Whopper®”.',
            'id_type' => '3',
            'id_user' => '2',
            'url' => 'https://www.cev.com/',
            'image' => 'https://conservatoriodeavila.es/webconser/sites/default/files/styles/large/public/field/image/notas.png?itok=96n8st3P',
            'lat' => '40.437562',
            'lon' => '-3.715500',
        ]);

        DB::table('events')->insert([
            'title' => 'Las horas de las prácticas son reducidas a 220 horas.',
            'description' => 'La cadena repartirá, desde las 19.54hs hasta las 20.20hs, un total de 100 menús Whopper® como premio para los 100 primeros usuarios que publiquen en su perfil de Instagram usando uno de los “Filter of the Whopper®”.',
            'id_type' => '3',
            'id_user' => '3',
            'url' => 'https://www.cev.com/',
            'image' => 'https://www.solvam.es/wp-content/uploads/2018/04/FCT_EUROPA.png',
            'lat' => '40.437562',
            'lon' => '-3.715500',
        ]);

        //Noticias
        DB::table('events')->insert([
            'title' => 'Los asesores de Trump no saben cómo decirle que el sitio en el que se ha encerrado no es el búnker de la Casa Blanca sino el cuarto de la fregona',
            'description' => 'Nadie en la Casa Blanca sabe cuánto tiempo más seguirá el presidente encerrado en el cuarto de la lavadora, pero por suerte hay lejía y otros productos de limpieza con los que también se siente seguro ante la amenaza del coronavirus.',
            'id_type' => '4',
            'id_user' => '2',
            'url' => 'https://www.elmundotoday.com/2020/06/los-asesores-de-trump-no-saben-como-decirle-que-el-sitio-en-el-que-se-ha-encerrado-no-es-el-bunker-de-la-casa-blanca-sino-el-cuarto-de-la-fregona/',
            'image' => 'https://emtstatic.com/2020/06/trump-696x464.jpg',
            'lat' => '41.439155',
            'lon' => '2.165638',
        ]);

        DB::table('events')->insert([
            'title' => 'Juan Carlos I dona 50 millones de litros de leche y aceite a Corinna',
            'description' => 'Y no sería la única, pues la Fiscalía cree que el Rey Juan Carlos ha estado donando grandes cantidades de leche a diferentes mujeres durante los últimos años.',
            'id_type' => '4',
            'id_user' => '2',
            'url' => 'https://www.elmundotoday.com/2020/06/juan-carlos-i-dona-50-millones-de-litros-de-leche-y-aceite-a-corinna/',
            'image' => 'https://emtstatic.com/2020/06/Captura-de-pantalla-2020-06-02-a-las-14.20.33-696x402.png',
            'lat' => '40.483433',
            'lon' => '-3.801658',
        ]);
    }
}
