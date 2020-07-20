<?php

namespace App\Http\Controllers;

use App\Users;
use DB;
use Image;
use Exception;
use App\Roles;
use App\Privacity;
use App\Friend;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Groups;
use App\Belong;

class UsersController extends Controller
{

    public function post_create()
    {
        try {
            //Comprobación de si los campos estan rellenados
            if (empty($_POST['email']) || empty($_POST['password']) )
            {
                return $this->createResponse(401, 'Debes rellenar todos los campos');
            }

            //Comprobación de si las contraseñas coinciden
            if (($_POST['password']) != ($_POST['repeatPassword'])) {
                return $this->createResponse(400, 'Las contraseñas no coinciden');
            }

            //La contraseña debe de tener de 5 a 12 caracteres
            if(strlen($_POST['password']) < 5 || strlen($_POST['password']) > 12){
                return $this->createResponse(400, 'La contraseña ha de tener entre 5 y 12 caracteres');
            }

            //Se asignan en variables los campos obtenidos
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeatPassword'];
            $username = explode("@", $email)[0];

            //Comprobación si el email está registrado
            $userDB = Users::where('email', $email)->first();
            if ($userDB != null) {
                return $this->createResponse(400, 'El email ya está registrado');
            }

            //Comprobación de que el email venga en el formato correcto
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->createResponse(400, 'Introduzca un email válido');
            }

            //Correos aceptados para el registro
            $mockData = [
                "jeff@gmail.com",
                "juanma@gmail.com",
                "carlos@gmail.com",
                "daniel@gmail.com",
                "dario@gmail.com",
                "roberto@gmail.com",
                "miguel@gmail.com",
                "julio@gmail.com",
                "mariofrigi@gmail.com"
            ];

            //Comprobación de que el email este en la BBDD del mocked Data
            $mails = $mockData;
            if (!in_array($email, $mails)) {

                return $this->createResponse(401, 'Introduce un email que esté en la base de datos');
            }

            //Se crea una privacidad automatica para el usuario (luego se puede modificar)
            $newPrivacity = new Privacity();
            $newPrivacity->phone = 0;
            $newPrivacity->localization = 0;
            $newPrivacity->save();

            //Se genera el objeto usuario con los campos anteriormente obtenidos y se guarda
            $newUser = new Users();
            $newUser->email = $email;
            $newUser->is_registered = 1;
            $newUser->id_rol = 4; //Se asigna el rol de alumno por defecto
            $newUser->password = Hash::make($password);
            $newUser->id_privacity = $newPrivacity->id;
            $newUser->username = $username;
            $newUser->name = $username;
            $newUser->save();

            return $this->createResponse(200, 'Usuario registrado');
        }
        catch (Exception $e)
        {
            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function post_changepassword()
    {
        //Se obtiene el token del usuario
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        //Se asignan en variables los campos obtenidos
        $password = $_POST['password'];
        $lastPassword = $_POST['lastPassword'];

        //Comprobación de que la antigua contraseña y la nueva no son la misma
        if($password == $lastPassword){
            return $this->createResponse(400, 'La contraseña nueva coincide con la antigua');
        }

        //Comprobación de que la contraseña antigua es igual a la que hay en la BBDD y si es, la contraseña se cambia a la nueva introducida por el usuario
        if(Hash::check($lastPassword, $userData->password)){
            $id = $userData->id;
            $user = Users::find($id);
            $user->password = Hash::make($password);
            $user->save();

            //Array con parte de la información del usuario
            $array = $arrayName = array
                (
                'id' => $userData->id,
                'email' => $userData->email,
                'username' => $userData->username,
                'password' => Hash::make($password),
                'id_rol' => $userData->id_rol,
                'id_privacity' => $userData->id_privacity,
                'group' => $userData->group
                );

            //El token es el array codificado mas la key
            $token = JWT::encode($array, $key);

            return $this->createResponse(200, 'Contraseña cambiada',  ['token'=>$token]);

        }else{
            return $this->createResponse(400, 'La contraseña antigua no es correcta');
        }

    }

    public function post_login()
    {
        try {

            //Comprobación de si los campos estan rellenados
            if (empty($_POST['email']) || empty($_POST['password']) )
            {
                return $this->createResponse(400, 'Parametros incorrectos');
            }

            //Se asignan en variables los campos obtenidos
            $email = $_POST['email'];
            $password = $_POST['password'];

            //Comprobación de si el usuario existe mediante el email
            $users = Users::where('email', $email)->get();
            if ($users->isEmpty()) {
                return $this->createResponse(400, 'Ese usuario no existe');
            }

            //Comprobación de si las contraseñas coinciden para hacer el login
            if(self::checkLogin($email, $password)){

                //Obtención de toda la información de el usuario filtrado por email
                $userSave = Users::where('email', $email)->first();

                //Comprobación de si se obtienen coordenadas y si no se asignan como (0,0)
                if(!empty($_POST['lon']) && !empty($_POST['lat']) ) {
                    $lon = $_POST['lon'];
                    $lat = $_POST['lat'];
                    $userSave->lon = $lon;
                    $userSave->lat = $lat;
                    $userSave->save();
                }

                //Array con parte de la información del usuario
                $array = $arrayName = array
                (
                'id' => $userSave->id,
                'email' => $email,
                'username' => $userSave->username,
                'password' => Hash::make($password),
                'id_rol' => $userSave->id_rol,
                'id_privacity' => $userSave->id_privacity,
                'group' => $userSave->group
                );

                //El token es el array codificado mas la key
                $key = $this->key;
                $token = JWT::encode($array, $key);

                //Obtención de la privacidad del usuario en un objeto
                $privacity = Privacity::find($userSave->id_privacity);

                return $this->createResponse(200, 'login correcto', ['token'=>$token, 'user' => $userSave, 'privacity' =>$privacity]);

            }
            else
            {
              return $this->createResponse(400, 'incorrect password');
            }
        }
        catch (Exception $e)
        {
            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function post_update(Request $request)
    {
        //Se obtiene el token del usuario
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        //Se obtiene el id del usuario y se  obtiene toda su información
        $id_user = $userData->id;
        $id = $_POST['id'];
        $user = Users::find($id_user);

        //Comprobación de si el usuario que esta intentando editar el usuario es el mismo usuario o el admin
        if ($user->id_rol != 1 && $userData->id != $id) {
            return $this->createResponse(401, 'No tienes permiso');
        }

        //Comprobación de si se obtiene el id del usuario
        if (empty($_POST['id'])) {
            return $this->createResponse(400, 'Introduce la id del usuario');
        }

        try {
            //Comprobación si el usuario existe
            $userBD = Users::find($id);
            if ($userBD == null) {
                return $this->createResponse(400, 'El usuario no existe');
            }

            //Almacenamiento de la imagen recibida con un nombre generado en función del id del usuario y alamacenado en la ruta especificada
            $photo = $request->file('photo');
            if (!empty($photo)) {
                $filename = 'user_' . $id_user . '_' . $photo->getClientOriginalName();
                $saveRoute = '/uploads/profile/';
                Image::make($photo)->resize(400,400)->save(public_path($saveRoute . $filename));
                $userBD->photo = $this->getGlobalPath($saveRoute, $filename);
            }

            //Comprobaciones del resto de campos
            if (!empty($_POST['name']) ) {
                $userBD->name = $_POST['name'];
            }
            if (!empty($_POST['email']) ) {
                $userBD->email = $_POST['email'];
            }
            if (!empty($_POST['phone']) ) {
                $userBD->phone = $_POST['phone'];
            }
            if (!empty($_POST['birthday']) ) {
                $userBD->birthday = $_POST['birthday'];
            }
            if (!empty($_POST['description']) ) {
                $userBD->description = $_POST['description'];
            }

            //Comprobaciones de si la privacidad recibida es valida
            if (isset($_POST['phoneprivacity']) && isset($_POST['localizationprivacity'])) {

                if ($_POST['phoneprivacity'] != 0 && $_POST['phoneprivacity'] != 1){
                    return $this->createResponse(400, 'Valor de phoneprivacity no válido, debe ser 0 ó 1');
                }

                if ($_POST['localizationprivacity'] != 0 && $_POST['localizationprivacity'] != 1){
                    return $this->createResponse(400, 'Valor de localizationprivacity no válido, debe ser 0 ó 1');
                }

                //Generación de la nueva privacidad del usuario
                $privacity = Privacity::find($userBD->id_privacity);
                $privacity->phone = $_POST['phoneprivacity'];
                $privacity->localization = $_POST['localizationprivacity'];
                $privacity->save();

            }

            //Comprobación de que el rol asignado sea valido
            if (!empty($_POST['id_rol']) ) {
                $rolDB = Roles::find($_POST['id_rol']);
                if ($rolDB == null) {
                    return $this->createResponse(400, 'Rol no valido');
                }
                $userBD->id_rol = $_POST['id_rol'];
            }

            //Array con parte de la información del usuario
            $array = $arrayName = array
                (
                'id' => $userBD->id,
                'email' => $userBD->email,
                'username' => $userBD->username,
                'password' => $userBD->password,
                'id_rol' => $userBD->id_rol,
                'id_privacity' => $userBD->id_privacity,
                'group' => $userBD->group
                );

            $userBD->save();

            $token = JWT::encode($array, $key);

            return $this->createResponse(200, 'Usuario actualizado', ['token'=>$token, 'user'=>$userBD]);

        } catch (Exception $e) {

           return $this->createResponse(500, $e->getMessage());

        }
    }

    public function post_delete()
    {
        //Se obtiene el token del usuario
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        //Se coje el id del usuario
        $id_user = $userData->id;

        //Comprobación de si el usuario actual puede borrar ese usuario
        $user = Users::find($id_user);
        if ($user->id !== 1) {
            return $this->createResponse(401, 'No tienes permiso');
        }

        //Se asignan en variables los campos obtenidos
        $id = $_POST['id'];

        //Comprobación de que se obtenga la id del usuario
        if (empty($_POST['id'])) {
            return $this->createResponse(400, 'Introduce la id del usuario');
        }

        try {
            //Comprobación de si el usuario con ese id existe
            $userBD = Users::find($id);
            if ($userBD == null) {
                return $this->createResponse(400, 'El usuario no existe');
            }

            //Borrado del usuario
            $userBD->delete();

            return $this->createResponse(200, 'Usuario borrado');
        } catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function post_recover(Request $request)
    {
        //Comprobación de si se han rellenado los campos requeridos
        $email = $_POST['email'];
        if (empty($_POST['email']))
        {
            return $this->createResponse(401, 'Introduzca su email');
        }

        //Comprobación de que el email introducido exista en la BBDD y este registrado
        $users = Users::where('email', $email)->first();
        if ($users == null) {
            return $this->createResponse(400, 'Ese usuario no existe');
        }

        try{
            //Comprobación de si el email existe en la BBDD
            if (self::recoverPassword($email)) {
                //Generación de un string que será la nueva contraseña del usuario
                $dataEmail = array(
                    'pwd' => Str::random(10),
                );

                //Función de laravel para envio de emails
                Mail::send('emails.welcome', $dataEmail, function($message){
                    $emailRecipient = $_POST['email'];
                    $message->from(env('MAIL_USERNAME'), 'Recuperación pass');
                    $message->to($emailRecipient)->subject('Nueva contraseña');
                });

                //Se asigna la contraseña generada al campo y se guarda
                $users->password = Hash::make($dataEmail['pwd']);
                $users->save();

                return $this->createResponse(200, "Correo enviado");
            }
            else
            {
                return $this->createResponse(403, "Los datos no son correctos");
            }
        } catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function post_sendRequest()
    {
        //Se obtiene el token del usuario
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        //Se asignan en variables los campos obtenidos
        $id_user = $_POST['id_user'];

        //Comprobación de que el id de usuario este rellenado
        if (empty($_POST['id_user']))
        {
          return $this->createResponse(400, 'Introduzca la ID de un usuario');
        }

        //Comprobación de que el usuario no se la envie a si mismo
        if($userData->id == $id_user){
            return $this->createResponse(400, 'No se puede enviar una petición a ti mismo');
        }

        try {
            //Comprobación de si existe el usuario
            $userBD = Users::where('id', $id_user)->first();
            if ($userBD == null)
            {
                return $this->createResponse(400, 'No existe el usuario');
            }

            //Obtención del id de usuario emitente de la petición
            $userId = $userData->id;
            $friend = Friend::where('state' , 1)
                        ->where('id_user_send', $userData->id)
                        ->where('id_user_receive', $id_user)
                        ->orWhere(function ($query) use($id_user, $userId) {
                            $query->where('id_user_send', $id_user)
                                ->where('id_user_receive', $userId);
                        })
                        ->get();

            //Comprobación de si ya son amigos o ya tiene una petición activa
            if($friend[0]->state == 2){
                return $this->createResponse(400, 'Ya sois amigos');
            }else if($friend[0]->state == 1){
                return $this->createResponse(400, 'Ya hay una petición existente entre ambos usuarios');
            }



            $newFriend = new Friend();
            $newFriend->id_user_send = $userData->id;
            $newFriend->id_user_receive = $id_user;
            $newFriend->state = 1;
            $newFriend->save();

            return $this->createResponse(200, 'Peticion enviada a '. $userBD->name);

        } catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function post_responseRequest()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        if (empty($_POST['type']) || empty($_POST['id_user']))
        {
          return $this->createResponse(400, 'Faltan parámetros obligatorios (id_user o type) ');
        }

        if ($_POST['type'] != 2 && $_POST['type'] != 3) {
            return $this->createResponse(400, 'El tipo enviado no es valido');
        }


        //ID del usuario a aceptar
        $id_user = $_POST['id_user'];
        //Estado de aceptar o denegar (2=Aceptar 3=Denegar)
        $type = $_POST['type'];

        try {

            $friend = Friend::where('state' , 1)
                ->where('id_user_send', $userData->id)
                ->where('id_user_receive', $id_user)
                ->first();
            $arrFriend = (array)$friend;
            $isFriendEmpty = array_filter($arrFriend);
            if (empty($isFriendEmpty))
            {
                return $this->createResponse(400, 'No existe la petición de amistad');
            }
            $friend->state = $type;
            $friend->save();

            if ($type==2) {
                return $this->createResponse(200, 'Solicitud de amistad Aceptada');
            }else{
                $friend->delete();
                return $this->createResponse(200, 'Solicitud de amistad Denegada');
            }

        } catch (Exception $e) {

                return $this->createResponse(500, $e->getMessage());

        }
    }

    public function post_deleteFriend()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        if (empty($_POST['id_user']))
        {
          return $this->createResponse(400, 'Introduzca la ID del Usuario');
        }
        $id_user = $_POST['id_user'];

        try {
            $userBD = Users::where('id', $id_user)->first();

            if ($userBD == null) {
                return $this->createResponse(400, 'No existe el usuario');
            }

            $userId = $userData->id;
            $friend = Friend::where('state' , 2)
                        ->where('id_user_send', $userData->id)
                        ->where('id_user_receive', $id_user)
                        ->orWhere(function ($query) use($id_user, $userId) {
                                $query->where('id_user_send', $id_user)
                                      ->where('id_user_receive', $userId);
                    })
                    ->first();
            $arrFriend = (array)$friend;
            $isFriendEmpty = array_filter($arrFriend);

            if (empty($isFriendEmpty))
            {
                return $this->createResponse(400, 'Este usuario no existe en tu lista de amigos');
            }

            $friend->delete();
            return $this->createResponse(200, 'Has borrado al usuario de tu lista de amigos');

            }
            catch (Exception $e)
            {
                return $this->createResponse(500, $e->getMessage());
            }
    }

    public function post_cancelRequest()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        if (empty($_POST['id_user']))
        {
          return $this->createResponse(400, 'Introduzca la ID del Usuario');
        }
        $id_user = $_POST['id_user'];

        try {
            $userBD = Users::where('id', $id_user)->first();

            if ($userBD == null) {
                return $this->createResponse(400, 'No existe el usuario');
            }

            $friend = Friend::where('state' , 1)
                ->where('id_user_send', $userData->id)
                ->where('id_user_receive', $id_user)
                ->first();
            $arrFriend = (array)$friend;
            $isFriendEmpty = array_filter($arrFriend);
            if (empty($isFriendEmpty))
            {
                return $this->createResponse(400, 'No existe la petición de amistad');
            }

            $friend->delete();

            return $this->createResponse(200, 'Petición de amistad cancelada');

        } catch (Exception $e) {

            return $this->createResponse(500, $e->getMessage());

        }
    }

    public function post_insertUser()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id_user = $userData->id;
        $user = Users::find($id_user);
        if ($user->id !== 1) {
            return $this->createResponse(401, 'No tienes permiso');
        }
        if (empty($_POST['email']) || empty($_POST['id_rol']) || empty($_POST['id_group']) || empty($_POST['name'])) {
            return $this->createResponse(400, 'Falta parametro email, id_rol, id_group, name');
        }
        $email = $_POST['email'];
        $id_rol = $_POST['id_rol'];
        $id_group = $_POST['id_group'];
        $name = $_POST['name'];
        $username = explode("@", $email)[0];

        try {
            $rolDB = Roles::find($id_rol);
            if ($rolDB == null) {
               return $this->createResponse(400, 'Rol no valido (1-> admin, 2-> coordinador, 3-> profesor, 4-> alumno)');
            }

            $groupDB = Groups::find($id_group);
            if ($groupDB == null) {
                return $this->createResponse(400, 'id_group no válido');
            }

            $userDB = Users::where('email', $email)->first();

            if ($userDB != null) {
                return $this->createResponse(400, 'El email ya existe');
            }

            $newUser = new Users();
            $newUser->email = $email;
            $newUser->is_registered = 1;
            $newUser->id_rol = $id_rol;
            $newUser->password = "temporal";
            $newUser->id_privacity = 1;
            $newUser->name = $name;
            $newUser->username = $username;
            $newUser->save();



            $belongDB = new Belong();
            $belongDB->id_user = $newUser->id;
            $belongDB->id_group = $groupDB->id;
            $belongDB->save();

            return $this->createResponse(200, 'Usuario insertado con exito');

        } catch (Exception $e)
        {
            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function get_allusers()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id_user = $userData->id;
        $user = Users::find($id_user);
        $users = Users::where('is_registered', 1)->get();
        $userNames = [];
        $userRoles = [];
        foreach ($users as $user) {
            array_push($userNames, $user->username);
            array_push($userRoles, $user->id_rol);
        }
        // return response()->json([
        //     'users' => $userNames,
        //     'roles' => $userRoles,
        // ]);
                return $this->createResponse(200, 'Listado de usuarios', $users);
    }

    public function get_validateMail()
    {
        if (empty($_GET['email'])) {
            return $this->createResponse(400, 'Faltan parámetros');
        }
        $email = $_GET['email'];

        try {
            $userBD = Users::where('email', $email)->first();
            if($userBD != null)
            {
                return $this->createResponse(200, 'Correo valido',array('email'=>$email, 'id'=>$userBD->id) );
            }
            else
            {
                return $this->createResponse(400, 'Email no valido');
            }

        } catch (Exception $e) {

                return $this->createResponse(500, $e->getMessage());
        }
    }

    public function get_friends()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));


        $userId = $userData->id;

        $friends = Friend::where('state', 2)
                    ->where('id_user_receive', $userId)
                    ->orWhere('state',2)
                    ->where('id_user_send', $userId)
                    ->get();

        $friendIds = [];


        foreach ($friends as $friend) {

            if ($friend->id_user_send == $userId) {
                $userDetail = Users::findOrFail($friend->id_user_receive);
                array_push($friendIds, $userDetail);

            }
            if ($friend->id_user_receive == $userId) {
                $userDetail = Users::findOrFail($friend->id_user_send);
                array_push($friendIds, $userDetail);
            }
        }

        // return response()->json([
        //     'friends' => $friendIds,

        // ]);
        return $this->createResponse(200, 'Lista de amigos', $friendIds);

        $users = Users::where('id', $friend->id_user_receive)
                        ->orWhere('id', $friend->id_user_send)
                        ->get();
        $userIds = [];
        foreach ($users as $user) {

            if ($user->id == $userId) {
                array_push($friendIds, $friend->id_user_receive);
            }
        }

        if (empty($isFriendEmpty))
            {
                return $this->error(400, 'El usuario no tiene amigos');
            }
            else{
                return $this->error(400, 'holi');
            }
    }

    public function get_userById()
    {
        try {
            $headers = getallheaders();
            $token = $headers['Authorization'];
            $key = $this->key;


            if ($token == null)
            {
                return $this->createResponse(400, 'Permiso denegado');
            }

            $userData = JWT::decode($token, $key, array('HS256'));
            $id_user = $userData->id;

            $id = $_GET['id'];


            $userDB = Users::find($id);

            if ($userDB == null)
            {
                return $this->createResponse(400, 'El usuario no existe');
            }

            $privacity = Privacity::find($userDB->id_privacity);

            $friend = Friend::where('id_user_receive', $id)
                        ->where('id_user_send', $id_user)
                        ->orWhere(function ($query) use($id_user, $id) {
                             $query->where('id_user_send', $id)
                                   ->where('id_user_receive', $id_user);
                    })
                    ->first();

            // return $this->createResponse(200, 'Usuario devuelto: ' . $userDB->name . ' Privacity: ' . $privacity->phone . ' Friend: ' . $friend);
            return $this->createResponse(200, 'Usuario devuelto', array('user' => $userDB, 'privacity' => $privacity, 'friend' => $friend));


        } catch (Exception $e) {

            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function get_user()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id = Users::where('email', $userData->email)->first()->id;
        if (empty($_GET['search']))
        {
          return $this->createResponse(400, 'Falta parámetro obligatorio (search)');
        }
        $search = $_GET['search'];
        $search = '%'.$search.'%';
        $users = Users::where('email', 'like', $search)
                    ->orWhere('username', 'like', $search)
                    ->orWhere('name', 'like', $search)
                    ->get();

        return $this->createResponse(200, 'Usuario', $users);

    }

    public function get_requests()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        $friends = Friend::where('id_user_send', $userData->id)
                       ->orWhere('id_user_receive', $userData->id)
                       ->get();

        $friends = Friend::where('state', 1)
                       ->where('id_user_send', $userData->id)
                       ->orWhere('id_user_receive', $userData->id)
                       ->get();

        $friendNames = [];

        foreach ($friends as $friend) {

            if ($friend->id_user_send == $userData->id) {
                $userDetail = Users::findOrFail($friend->id_user_receive);
                array_push($friendNames, $userDetail);

            }
            if ($friend->id_user_receive == $userData->id) {
                $userDetail = Users::findOrFail($friend->id_user_send);
                array_push($friendNames, $userDetail);
            }
        }

        // $results = DB::select('select * from friends where id_user_send = 1', array(1));
        return $this->createResponse(200, 'Peticiones devueltas', array('requests' => $friends));
    }

    //     $friends = \DB::query('SELECT * FROM users
    //                                     JOIN friend ON friend.id_user_send = '.$user->data->id.'
    //                                     AND users.id = friend.id_user_receive
    //                                     UNION
    //                                     SELECT * FROM users
    //                                     JOIN friend ON friend.id_user_receive = '.$user->data->id.'
    //                                     AND users.id = friend.id_user_send

    //                                     ')->as_assoc()->execute();

    public function userNotRegistered($email)
    {
        $users = Users::where('email', $email)->get();
        foreach ($users as $user) {
            if ($user->email == $email) {
                return false;
            }
            else{
                return true;
            }
        }
    }
}
