<?php 

// User custom 404 code handle
function return_404(){
    response([
        'message'=>'Not found'
    ],404);
    exit();
}

// Optional function (good for documentation)
function home(){
    response([
        'message'=>'Welcome in PHP Rose API'
    ],200);
}

// Example1 controller - simple request and response with code 200 
function example_users(){
    $data = DB::query("SELECT * FROM users");
    response([
        'data'=>$data
    ],200);
}

// Example2 controller  - request with params and response
function example_user(){
    if(!isset($_POST['id'])){ // check if user send ID (if not:)
        response([
            'message'=>'Id is required'
        ],400); // send response with status code 400
        exit(); // and end response
    }
    // User send id : continue
    $id = $_POST['id']; // get param (ID)
    $data = DB::query("SELECT * FROM users WHERE id=:id",['id'=>$id]); // bind param and execure query
    response([
        'data'=>$data
    ],200); // And return response with status code 200
}

// Firsth run, create table for authorization, after firsth run DELETE THIS
function setup(){
    DB::query("CREATE TABLE IF NOT EXISTS users (id int NOT NULL AUTO_INCREMENT, login text NOT NULL, password text NOT NULL, permission int NOT NULL,PRIMARY KEY (id))");
    response([
        'message'=>'Query executed'
    ],200);
}