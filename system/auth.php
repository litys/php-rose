<?php
// Change ONLY secret_key and token_exp (if you want), nothing else

require "vendor/autoload.php";
use \Firebase\JWT\JWT;

class AUTH {
    private static $list_auth_get = [];
    private static $list_auth_post = [];
    private static $secret_key = '__secret_key__';
    private static $token_exp = 600; // token valid in seconds
    
    public static function add_permission($url,$permission,$method){
        switch ($method) {
            case 'GET':
                self::$list_auth_get[$url]=$permission;
                break;
            case 'POST':
                self::$list_auth_post[$url]=$permission;
                break;
            default:
                // protection
                response([
                    'message'=>'Method '.$method.' in routing now allowed'
                ],406);
                exit();
        }
    }
    
    public static function create_token($user_id,$user_name,$permission){
        $token = [
            'sub' => $user_id,
            'name' => $user_name,
            'permission' => $permission,
            'iat' => time()
        ];
    
        $jwt = JWT::encode($token, base64_decode(strtr(self::$secret_key, '-_', '+/')), 'HS256');
        return $jwt;
    }
    
    public static function read_token($jwt){
        $decoded = JWT::decode($jwt, base64_decode(strtr(self::$secret_key, '-_', '+/')), ['HS256']);
        return $decoded;
    }

    public static function check_permission($method,$request){
        // 0 - allowed for all

        switch ($method) {
            case 'GET':
                $permission = self::$list_auth_get[$request];
                break;
            case 'POST':
                $permission = self::$list_auth_post[$request];
                break;
            default:
                response([
                    'message'=>'Method now allowed'
                ],405);
                exit();
        }

        // If route require special permission (not for all)
        if($permission) {
            // Check if user is logged
            $header = apache_request_headers(); 
            if(!isset($header["Token"])) {
                response([
                    'message'=>'User not logged'
                ],401);
                exit();
            } else {
                try {
                    $decoded = self::read_token($header["Token"]);
                    if($decoded->iat+self::$token_exp<time()){
                        response([
                            'message'=>'Token has expired'
                        ],401);
                        exit();
                    }
                    if(!($decoded->permission<=$permission)){
                        response([
                            'message'=>'You do not have permission to view that route'
                        ],403);
                        exit();
                    }
                } catch (Exception $e){
                    response([
                        'message'=>'Handicapped token'
                    ],406);
                    exit();
                }
            }
        }

    }
}

  
  