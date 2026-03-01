<?php

function login($request) {

    $username = $request -> {'username'};
    $password = $request -> {'password'};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $user_info = mysqli_fetch_assoc($db -> query_db("select user_id, username, user_password, account_type, account_status from users where username = '$username';"));

    if (empty($user_info)) {

        http_response_code(404);
        
        $response_json = [
            "code_type" => "not found",
            "msg" => "User not found"
        ];

    }else if ($user_info['account_status'] != "active"){
        http_response_code(403);
        
        $response_json = [
            "code_type" => "forbidden",
            "msg" => "Inactive account"
        ];
    
    }else if ($user_info['user_password'] != $password){
        http_response_code(403);
        
        $response_json = [
            "code_type" => "unauthorized",
            "msg" => "Incorrect password"
        ];

    }else{

        session_start();

        $_SESSION["username"] = $user_info["username"];
        $_SESSION["account_type"] = $user_info["account_type"];
        $_SESSION["user_id"] = $user_info["user_id"];

        http_response_code(200);
        
        $response_json = [
            "code_type" => "ok",
            "msg" => "Login succesful"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function get_session(){

    $response_json = [];

    session_start();
    
    if(!isset($_SESSION['user_id'])){
        http_response_code(404);
    
        $response_json = [
            "code_type" => "not found",
            "msg" => "Session not found"
        ];
    
    }else {
        http_response_code(200);

        $response_json = [
            "username" => $_SESSION["username"],
            "account_type" => $_SESSION["account_type"],
            "user_id" => $_SESSION["user_id"]
        ];

    }

    return json_encode($response_json);

}

function logout(){

    $response_json = [];

    session_start();

    if(!isset($_SESSION['user_id'])){
        http_response_code(404);
    
        $response_json = [
            "code_type" => "not found",
            "msg" => "Session not found"
        ];
    
    }else{

        session_destroy();

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Logged out"
        ];
    }

    return json_encode($response_json);

}