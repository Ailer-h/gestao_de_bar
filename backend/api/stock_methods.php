<?php

function add_stock($request) {

    $nome = $request -> {'nome'};
    $descricao = $request -> {'descricao'};
    $estoque_minimo = $request -> {'estoque_minimo'};
    $unidade_medida = $request -> {'unidade_medida'};
    $id_fornecedor = $request -> {'id_fornecedor'};
    $valor_unitario = $request -> {'valor_unitario'};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $db -> query_db("insert into estoque (nome, descricao, estoque_minimo, unidade_medida, id_fornecedor, valor_unitario) values ('$nome', '$descricao', '$estoque_minimo', '$unidade_medida', '$id_fornecedor', '$valor_unitario')");

    http_response_code(201);

    $response_json = [
        "code_type" => "created",
        "msg" => "Item added"
    ];

    $db -> end_connection();

    return json_encode($response_json);

}

function get_stock($item_id = null){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $query_str = "select id, nome, descricao, qtd_em_estoque, estoque_minimo, unidade_medida, id_fornecedor, valor_unitario, status from estoque;";

    if ($item_id !== null){

        $query_str = "select id, nome, descricao, qtd_em_estoque, estoque_minimo, unidade_medida, id_fornecedor, valor_unitario, status from estoque where id = $item_id;";
        
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
            "msg" => "No items found"
        ];
    
    }

    return json_encode($response_json);

}

function update_stock($item_id, $request){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select id, nome, descricao, qtd_em_estoque, estoque_minimo, unidade_medida, id_fornecedor, valor_unitario, status from estoque where id = $item_id and status = 'active';"));

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

        $db -> query_db("update estoque set " . implode(", ", $str_update) . " where id = $item_id");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Item updated"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function delete_stock($item_id){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select status from estoque where id = $item_id and status = 'active';"));

    if (empty($item)){

        http_response_code(400);

        $response_json = [
            "code_type" => "bad request",
            "msg" => "Invalid id"
        ];

    }else{

        $db -> query_db("update estoque set status = 'inactive' where id = $item_id;");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Item deleted"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}