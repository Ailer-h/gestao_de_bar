<?php

function add_stock_entry($request) {

    $id_produto = $request -> {"id_produto"};
    $qtd = $request -> {"qtd"};
    $valor_total = $request -> {"valor_total"};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select status from estoque where id = $id_produto;"));

    if (empty($item)){

        http_response_code(404);
        
        $response_json = [
            "code_type" => "not found",
            "msg" => "Item not foun"
        ];

    }else if ($item['status'] != "active"){
        http_response_code(403);
        
        $response_json = [
            "code_type" => "forbidden",
            "msg" => "Inactive item"
        ];
    
    }else {

        $db -> query_db("insert into entradas_estoque (id_produto, qtd, preco) values ($id_produto, $qtd, $valor_total)");
        $db -> query_db("update estoque set qtd_em_estoque = qtd_em_estoque + $qtd where id = $id_produto;");

        http_response_code(201);
    
        $response_json = [
            "code_type" => "created",
            "msg" => "Item added"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function get_stock_entry($id_entrada = null){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $query_str = "select id_entrada, id_produto, qtd, preco, data_entrada from entradas_estoque;";

    if ($id_entrada !== null){

        $query_str = "select id_entrada, id_produto, qtd, preco, data_entrada from entradas_estoque where id_entrada = $id_entrada;";
        
    }

    $entries = $db -> query_db($query_str);

    while ($e = mysqli_fetch_assoc($entries)){
        array_push($response_json, $e);
    
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