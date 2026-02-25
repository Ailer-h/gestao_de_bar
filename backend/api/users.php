<?php

require_once __DIR__ . '/../mysql_connect.php';

include "users_methods.php";

header("Content-type: application/json");

$SUPPORTED_METHODS = ["GET","POST", "PUT", "DELETE"];

if (!in_array($_SERVER['REQUEST_METHOD'], $SUPPORTED_METHODS)){
    echo json_encode([
        "error_code" => 400,
        "message" => "Unsupported method"
    ]);

}else {

    $request_uri = explode("/",$_SERVER['REQUEST_URI']); 
    $response = [
        "method" => $_SERVER['REQUEST_METHOD'],
        "uri" => $request_uri
    ];

    switch ($_SERVER['REQUEST_METHOD']){
        case "GET":
            if (isset($request_uri[5])){
                echo $request_uri[5];
            
            }else{
                echo json_encode($request_uri);

            }

            break;
        
        case "POST":

            $req_body = json_decode(file_get_contents('php://input'));

            $response = add_user($req_body);

            break;

        case "PUT":
            break;

        case "DELETE":
            break;
    }
    
}