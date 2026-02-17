<?php

    include "utilities/mysql_connect.php";

    $data_vals = [];

    $query = mysqli_query($connection, "select id, nome, descricao, qtd_em_estoque, estoque_minimo, unidade_medida, id_fornecedor, valor_unitario, status from estoque");

    while ($data = mysqli_fetch_assoc($query)){

        $data_array = array(
            "id" => $data['id'],
            "nome" => $data['nome'],
            "descricao" => $data['descricao'],
            "qtd_em_estoque" => $data['qtd_em_estoque'],
            "estoque_minimo" => $data['estoque_minimo'],
            "unidade_medida" => $data['unidade_medida'],
            "id_fornecedor" => $data['id_fornecedor'],
            "valor_unitario" => $data['valor_unitario'],
            "status" => $data['status']
        );

        array_push($data_vals, $data_array);

    }

    echo json_encode($data_vals);

    mysqli_close($connection);

?>