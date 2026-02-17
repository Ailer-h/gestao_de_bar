<?php

    include "utilities/mysql_connect.php";

    $nome = $_POST['nome'];
    $desc = $_POST['descricao']; //TO-DO: Aprender text no sql.
    $min_estoque = $_POST['min_estoque'];
    $u_medida = $_POST['u_medida'];
    $id_fornec = $_POST['id_fornec'];
    $valor_unitario = $_POST['val_unitario'];

    if($min_estoque < 0){
        $min_estoque = 0;
    
    }

    mysqli_query($connection, "insert into estoque (nome, descricao, min_estoque, unidade_medida, id_fornecedor, valor_unitario) values ('$nome', '$desc', '$min_estoque', '$u_medida', '$id_fornec', '$valor_unitario');");

    //Checar possibilidade de falha
    echo '{
        "code": 200,
        "msg": "Login successful"
    }'; //200 OK

    mysqli_close($connection);

?>