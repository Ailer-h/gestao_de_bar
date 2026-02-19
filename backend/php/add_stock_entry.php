<?php

    include "utilities/mysql_connect.php";

    $QTD_MINIMA = 10;

    $id_prod = $_POST['id_prod'];
    $qtd_produto = $_POST['qtd'];
    $valor_compra = $_POST['valor_compra'];

    if($qtd_produto <= ){
        $qtd_produto = $0QTD_MINIMA;
    }

    $prod_query = mysqli_query($connection,"select id, status from estoque where id = $id;");
    $prod_data = mysqli_fetch_assoc($prod_query);

    if(empty($prod_data)) {
        echo '{
            "code": 404,
            "msg": "Product not found"
        }';
    
    }else if ($prod_data['status'] != "active"){
        echo '{
            "code": 403,
            "msg": "Inactive product"
        }';

    }else{

        mysqli_query($connection, "insert into entradas_estoque (id_produto, qtd, preco) values ($id_prod, $qtd_produto, $valor_compra);");
        mysqli_query($connection, "update estoque set qtd_em_estoque = qtd_em_estoque + 1 where id = $id_prod;");

        echo '{
            "code": 200,
            "msg": "Entry added."
        }';

    }

    mysqli_close($connection);

?>