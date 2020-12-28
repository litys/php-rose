<?php 
// login and register for authorization. 
function register(){
    if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['permission'])) {
        $login = $_POST['login'];
        $permission = $_POST['permission'];
        try {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } catch (Exception $e) {
            response([
                'message'=>'Password invalid',
                'error'=>$e
            ],406);
            exit();
        }
        $data = DB::query("SELECT * FROM users WHERE login=:login",['login'=>$login]);
        if(count($data)) {
            response([
                'message'=>'User exist'
            ],406);
        } else {
            DB::query("INSERT INTO users VALUES(null,:login,:password,:permission)",['login'=>$login,'password'=>$password,'permission'=>$permission]);
            response([
                'message'=>'Account created'
            ],200);
        }
    } else {
        response([
            'message'=>'login, password and permission required'
        ],400);
    }
}

function login(){
    if(isset($_POST['login']) && isset($_POST['password'])) {
        $login = $_POST['login'];
        $data = DB::query("SELECT * FROM users WHERE login=:login",['login'=>$login]);
        
        if(count($data)) {
            try {
                if(!password_verify($_POST['password'],$data[0]['password'])) {
                    response([
                        'message'=>'Bad password'
                    ],404);
                    exit();
                } 
            } catch (Exception $e) {
                response([
                    'message'=>'Password invalid',
                    'error'=>$e
                ],406);
                exit();
            }

            $token = AUTH::create_token($data[0]['id'],$data[0]['login'],$data[0]['permission']);
            response([
                'message'=>'Successfully logged',
                'token'=>$token
            ],200);
        } else {
            response([
                'message'=>'User not found'
            ],403);
        }
    } else {
        response([
            'message'=>'login and password required'
        ],403);
    }
}