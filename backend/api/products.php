<?php

require_once __DIR__ . '/../mysql_connect.php';
require_once __DIR__ . '/../error_handler.php';

include "products_methods.php";

set_exception_handler("ErrorHandler::handleError");

header("Content-type: application/json");

$SUPPORTED_METHODS = ["GET","POST", "PUT", "DELETE"];

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
            
            if (isset($request_uri[5])){
                $response = get_products($request_uri[5]);

            }else{
                $response = get_products();

            }

            break;
        
        case "POST":

            $req_body = json_decode(file_get_contents('php://input'));

            $response = add_products($req_body);
            
            break;

        case "PUT":
            
            if (isset($request_uri[5])){
                $req_body = json_decode(file_get_contents('php://input'));

                $response = update_products($request_uri[5], $req_body);

            }else{
                http_response_code(400);

                $response = json_encode([
                    "error_code" => 400,
                    "message" => "Must provide an id"
                ]);

            }

            break;

        case "DELETE":
            
            if (isset($request_uri[5])){

                $response = delete_products($request_uri[5]);

            }else{
                http_response_code(400);

                $response = json_encode([
                    "error_code" => 400,
                    "message" => "Must provide an id"
                ]);

            }

            break;
    }
 
    echo $response;

}