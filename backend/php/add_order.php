<?php
    include "utilities/mysql_connect.php"; 

    $n_comanda = $_POST['n_comanda'];

    $query = mysqli_query($connection, "select id from pedidos where n_comanda = $n_comanda and estado = 'Aberta';");

    if(!empty(mysqli_fetch_assoc($query))){
        echo '{
            "code": 403,
            "msg": "Tab already assigned to other open order."
        }'; //403 Forbidden
    
    }else{

        mysqli_query($connection, "insert into pedidos (n_comanda) values ($n_comanda)");

        $order_query = mysqli_fetch_assoc(mysqli_query($connection, "select id from pedidos order by id desc limit 1;"));
        $order_id = $order_query['id'];
        
        echo '{
            "code": 200,
            "msg": "Order [#'.$order_id.'] created"
        }'; //200 OK
    }

    mysqli_close($connection);

?>