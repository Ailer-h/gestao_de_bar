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

    $fornec_query = mysqli_query($connection, "select id, acc_status from fornecedores where id = $id_fornec;");
    $fornec_data = mysqli_fetch_assoc($fornec_query);

    if(empty($fornec_data)){
        echo '{
            "code": 404,
            "msg": "Supplier not found"
        }';
    
    }else if($fornec_data['acc_status'] != "active"){
        echo '{
            "code": 407,
            "msg": "Inactive supplier"
        }';
    
    }else{

        mysqli_query($connection, "insert into estoque (nome, descricao, min_estoque, unidade_medida, id_fornecedor, valor_unitario) values ('$nome', '$desc', '$min_estoque', '$u_medida', '$id_fornec', '$valor_unitario');");

        echo '{
            "code": 200,
            "msg": "Login successful"
        }'; //200 OK

    }

    mysqli_close($connection);

?>