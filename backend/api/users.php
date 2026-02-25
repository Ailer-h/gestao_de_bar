<?php

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
    
}