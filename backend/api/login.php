<?php

require_once __DIR__ . '/../mysql_connect.php';
require_once __DIR__ . '/../error_handler.php';

include "login_methods.php";

set_exception_handler("ErrorHandler::handleError");

header("Content-type: application/json");

$SUPPORTED_METHODS = ["GET","POST","DELETE"];

if (!in_array($_SERVER['REQUEST_METHOD'], $SUPPORTED_METHODS)){

    http_response_code(400);

    echo json_encode([
        "error_code" => 400,
        "message" => "Unsupported method"
    ]);

}else{

    $request_uri = explode("/",$_SERVER['REQUEST_URI']); 

    switch ($_SERVER['REQUEST_METHOD']){
        case "GET":
            
            $response = get_session();

            break;
        
        case "POST":

            $req_body = json_decode(file_get_contents('php://input'));

            $response = login($req_body);
            
            break;

        case "DELETE":
        
            $response = logout();

            break;
    }
 
    echo $response;

}