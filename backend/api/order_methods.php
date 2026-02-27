<?php

function add_order($request) {

    $n_tab = $request -> {'n_tab'};

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $tab = mysqli_fetch_assoc($db -> query_db("select id from pedidos where n_comanda = $n_tab and estado = 'Aberta';"));

    if (!empty($tab)) {
        http_response_code(408);

        $response_json = [
            "code_type" => "forbidden",
            "msg" => "Tab already assigned"
        ];
    
    }else {
        $db -> query_db("insert into pedidos (n_comanda) values ($n_tab)");
    
        $order_id = mysqli_fetch_assoc($db -> query_db("select id from pedidos where n_comanda = $n_tab order by id desc limit 1;"))["id"];

        http_response_code(201);
    
        $response_json = [
            "code_type" => "created",
            "msg" => "Order #$order_id placed"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}

function get_order($order_id = null){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $query_str = "select id, n_comanda, valor_pago, estado, data_abertura, data_fechamento from pedidos;";

    if ($order_id !== null){

        $query_str = "select id, n_comanda, valor_pago, estado, data_abertura, data_fechamento from pedidos where id = $order_id;";
        
    }

    $orders = $db -> query_db($query_str);

    while ($o = mysqli_fetch_assoc($orders)){
        array_push($response_json, $o);
    
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

function update_order($order_id, $request){

    $db = new DatabaseConnection();
    $db -> connect_to_db();

    $response_json = [];

    $item = mysqli_fetch_assoc($db -> query_db("select id, n_comanda, valor_pago, estado, data_abertura, data_fechamento from pedidos where estado = 'Aberta' and id = $order_id;"));

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

        $db -> query_db("update pedidos set " . implode(", ", $str_update) . " where id = $order_id;");

        http_response_code(200);

        $response_json = [
            "code_type" => "ok",
            "msg" => "Order updated"
        ];

    }

    $db -> end_connection();

    return json_encode($response_json);

}