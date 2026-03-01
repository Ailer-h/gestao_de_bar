<?php

use Dom\Mysql;

function add_suppliers($request) {

    $nome = $request -> {"nome"};
    $cnpj = $request -> {"cnpj"};
    $telefone = $request -> {"telefone"};
    $email = $request -> {"email"};
    $endereco = $request -> {"endereco"};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $fornec = mysqli_fetch_assoc($db -> query_db("select cnpj from fornecedores where cnpj = '$cnpj'"));

    if (!empty($fornec)) {

        http_response_code(403);

        $response_json = [
            "code_type" => "forbidden",
            "msg" => "CNPJ already in use"
        ];

    }else{

        $db -> query_db("insert into fornecedores (nome, cnpj, telefone, email, endereco) values ('$nome','$cnpj','$telefone','$email','$endereco')");

        http_response_code(201);
    
        $response_json = [
            "code_type" => "created",
            "msg" => "Supplier added"
        ];

    }


    $db -> end_connection();

    return json_encode($response_json);

}

function get_suppliers($supplier_id = null){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $query_str = "select id, nome, cnpj, telefone, email, endereco, creation_date, acc_status from fornecedores;";

    if ($supplier_id !== null){

        $query_str = "select id, nome, cnpj, telefone, email, endereco, creation_date, acc_status from fornecedores where id = $supplier_id;";
        
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
            "msg" => "No suppliers found"
        ];
    
    }

    return json_encode($response_json);

}

function update_suppliers($supplier_id, $request){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select id, nome, cnpj, telefone, email, endereco, creation_date, acc_status from fornecedores where id = $supplier_id;"));

    if (empty($item)){

        http_response_code(400);

        $response_json = [
            "code_type" => "bad request",
            "msg" => "Invalid id"
        ];

    }else{

        $str_update = [];

        foreach ($request as $key => $value){

            if ($key == "cnpj"){
                
                $cnpjs = mysqli_fetch_assoc($db -> query_db("select cnpj from fornecedores where cnpj = '$value' and acc_status = 'active';"));

                if (empty($cnpjs)){
                    array_push($str_update, "$key = '$value'");

                }


            }else{
                array_push($str_update, "$key = '$value'");

            }

        }

        $db -> query_db("update fornecedores set " . implode(", ", $str_update) . " where id = $supplier_id;");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Supplier updated"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function delete_suppliers($supplier_id){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select acc_status from fornecedores where id = $supplier_id and acc_status = 'active';"));

    if (empty($item)){

        http_response_code(400);

        $response_json = [
            "code_type" => "bad request",
            "msg" => "Invalid id"
        ];

    }else{

        $db -> query_db("update fornecedores set acc_status = 'inactive' where id = $supplier_id;");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Supplier deleted"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}