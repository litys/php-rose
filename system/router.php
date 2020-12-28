<?php
// Do not change anything here

// Simplification of appeals
function get($url,$function,$permission = 0){
    ROUTING::get($url,$function,$permission);
}
function post($url,$function,$permission = 0){
    ROUTING::post($url,$function,$permission);
}

// Return json with status code
function response($data,$status = 0){
    echo json_encode($data);
    if($status) http_response_code($status);
    else http_response_code(200);
}

class ROUTING {

    private static $list_get=[];
    private static $list_post=[];

    // Creating list of permisions and routes
    public static function get($url,$command, $permission) {
        self::$list_get[$command]=$url;
        AUTH::add_permission($url,$permission,'GET');
    }
    public static function post($url,$command, $permission) {
        self::$list_post[$command]=$url;
        AUTH::add_permission($url,$permission,'POST');
    }

    // Handle 404 (or use user 404 if exist)
    public static function return_404(){
        if(function_exists('return_404')) return_404();
        else {
            response([
                'message'=>'Route not found'
            ],404);
            exit();
        }
    }
    // After check permission run controller
    public static function handle_response($response){
        if($response) {
            try {
                $response();
            } catch (Exception $e){
                response([
                    'message'=>'Critical error in controller: '.$response,
                    'error'=>$e
                ],500);
                exit();
            }
        } else self::return_404();
    }

    // Handling user request. Firsth gate
    public static function handle_request($method, $request){
        switch ($method) {
            case 'GET':
                $response = array_search($request, self::$list_get);
                break;
            case 'POST':
                $response = array_search($request, self::$list_post);
                break;
            default:
                self::return_404();
        }
        // After examining request method check if user have permissions to view that route
        AUTH::check_permission($method, $request);
        self::handle_response($response);
    }
}