<?php

require_once __DIR__ . '/../../../mysql_connect.php';

header("Content-type: application/json");

include "user_methods/add_user.php";

$SUPPORTED_METHODS = ["GET","POST", "PUT", "DELETE"];

if (!in_array($_SERVER['REQUEST_METHOD'], $SUPPORTED_METHODS)){
    echo json_encode([
        "error_code" => 400,
        "message" => "Unsupported method"
    ]);

}else {

    switch ($_SERVER['REQUEST_METHOD']){
        case "GET":
            echo "GET";
            break;
        
        case "POST":
            
            break;

        case "PUT":
            echo "PUT";
            break;

        case "DELETE":
            echo "DELETE";
            break;
    }

    $response = [
        "method" => $_SERVER['REQUEST_METHOD']
    ];
    
    echo json_encode($response);
 
    function add_user($username, $password, $acc_type){

        $db = new DatabaseConnection();
        
        $db -> connect_to_db();

        $response_json = [];

        $user = $db -> use_query("select user_id, username, user_password, account_type, account_status from users where username = '$username'");

        if (!empty($user)){
            $response_json = [
                "code" => 403,
                "code_type" => "forbidden",
                "msg" => "User already exists"
            ];
    
        }else{

            //TO-DO: Adicionar criptografia para a senha. 

            $db -> use_query("insert into users (username, user_password, account_type) values ('$username','$password','$acc_type')");

            $response_json = [
                "code" => 200,
                "code_type" => "created",
                "msg" => "User added"
            ];

        }

        $db -> end_connection();

        return json_encode($response_json);

    }

}