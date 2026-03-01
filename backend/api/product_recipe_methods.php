<?php

function add_product_recipe($request) {

    $id_produto = $request -> {"id_produto"};
    $recipe = $request -> {"recipe"};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    foreach ($recipe as $ingredients){
        $id_ingrediente = $ingredients -> {'id_ingrediente'};
        $qtd_ingrediente = $ingredients -> {'qtd_ingrediente'};

        $db -> query_db("insert into relation_produtos_estoque (id_produto, id_estoque, qtd_ingrediente) values ('$id_produto', '$id_ingrediente', '$qtd_ingrediente');");
    }

    http_response_code(201);

    $response_json = [
        "code_type" => "created",
        "msg" => "Item added"
    ];

    $db -> end_connection();

    return json_encode($response_json);

}

function get_product_recipe($prod_id = null){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $query_str = "select id_produto, GROUP_CONCAT(id_estoque SEPARATOR ' | ') as ids_ingrediente, GROUP_CONCAT(qtd_ingrediente SEPARATOR ' | ') as qtds_ingrediente from relation_produtos_estoque group by id_produto;";

    if ($prod_id !== null){

        $query_str = "select id_produto, GROUP_CONCAT(id_estoque SEPARATOR ' | ') as ids_ingrediente, GROUP_CONCAT(qtd_ingrediente SEPARATOR ' | ') as qtds_ingrediente from relation_produtos_estoque where id_produto = $prod_id group by id_produto;";
        
    }

    $items = $db -> query_db($query_str);

    while ($i = mysqli_fetch_assoc($items)){

        $recipe = [];

        $ids_ingredientes = explode(" | ", $i['ids_ingrediente']);
        $qtds_ingredientes = explode(" | ", $i['qtds_ingrediente']);

        for ($ix = 0; $ix < count($ids_ingredientes); $ix ++){
            array_push($recipe, array(
                "id_ingrediente" => $ids_ingredientes[$ix],
                "qtd_ingrediente" => $qtds_ingredientes[$ix]
            ));
        }

        array_push($response_json, array(
            "id_produto" => $i['id_produto'],
            "recipe" => $recipe
        ));
    
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

function update_product_recipe($prod_id, $request){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db(""));

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

        $db -> query_db("");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Item updated"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function delete_product_recipe($prod_id){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db(""));

    if (empty($item)){

        http_response_code(400);

        $response_json = [
            "code_type" => "bad request",
            "msg" => "Invalid id"
        ];

    }else{

        $db -> query_db("");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Item deleted"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}