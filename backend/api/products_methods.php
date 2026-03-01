<?php

function add_products($request) {

    $name = $request -> {"name"};
    $value = $request -> {"value"};
    $sell_price = $request -> {"sell_price"};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $db -> query_db("insert into produtos (nome, valor_producao, valor_venda) values ('$name', '$value', '$sell_price');");

    http_response_code(201);

    $response_json = [
        "code_type" => "created",
        "msg" => "Product added"
    ];

    $db -> end_connection();

    return json_encode($response_json);

}

function get_products($product_id = null){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $query_str = "select id, nome, valor_producao, valor_venda, status from produtos;";

    if ($product_id !== null){

        $query_str = "select id, nome, valor_producao, valor_venda, status from produtos where id = $product_id;";
        
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

function update_products($product_id, $request){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select id, nome, valor_producao, valor_venda, status from produtos where id = $product_id and status = 'active';"));

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

        $db -> query_db("update produtos set " . implode(", ", $str_update) . " where id = $product_id");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Item updated"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function delete_products($product_id){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $product_info = mysqli_fetch_assoc($db -> query_db("select status from produtos where id = $product_id and status = 'active';"));

    if (empty($product_info)){

        http_response_code(400);

        $response_json = [
            "code_type" => "bad request",
            "msg" => "Invalid id"
        ];

    }else{

        $db -> query_db("update produtos set status = 'inactive' where id = $product_id;");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Item deleted"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}