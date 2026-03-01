<?php

function add_clients($request) {

    $nome = $request -> {"nome"};
    $telefone = $request -> {"telefone"};
    $cpf = $request -> {"cpf"};
    $email = $request -> {"email"};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $db -> query_db("insert into clientes (nome, telefone, cpf, email) values ('$nome', '$telefone', '$cpf', '$email');");

    http_response_code(201);

    $response_json = [
        "code_type" => "created",
        "msg" => "Client added"
    ];

    $db -> end_connection();

    return json_encode($response_json);

}

function get_clients($client_id = null){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $query_str = "select id, nome, telefone, cpf, email, creation_date, acc_status from clientes;";

    if ($client_id !== null){

        $query_str = "select id, nome, telefone, cpf, email, creation_date, acc_status from clientes where id = $client_id;";
        
    }

    $items = $db -> query_db($query_str);

    while ($i = mysqli_fetch_assoc($items)){
        array_push($response_json, $i);
    
    }

    $db -> end_connection();

    if (empty($response_json)){

        http_response_code(404);
        
        $response_json = [
            "code_type" => "not found",
            "msg" => "No clients found"
        ];
    
    }

    return json_encode($response_json);

}

function update_clients($client_id, $request){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select id, nome, telefone, cpf, email, creation_date, acc_status from clientes where id = $client_id;"));

    if (empty($item)){

        http_response_code(400);

        $response_json = [
            "code_type" => "bad request",
            "msg" => "Invalid id"
        ];

    }else{

        $str_update = [];

        foreach ($request as $key => $value){
            array_push($str_update, "$key = '$value'");
        }

        $db -> query_db("update clientes set " . implode(", ", $str_update) . " where id = $client_id");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Client updated"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function delete_clients($client_id){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select acc_status from clientes where id = $client_id;"));

    if (empty($item)){

        http_response_code(400);

        $response_json = [
            "code_type" => "bad request",
            "msg" => "Invalid id"
        ];

    }else{

        $db -> query_db("update clientes set acc_status = 'inactive' where id = $client_id");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Client deleted"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}