<?php

    //Conecta ao database

    $server = "127.0.0.1";
    $user = "root";
    $password = "";
    $database = "bar_do_wood";

    $connection = mysqli_connect($server,$user,$password,$database) or die ("Problemas para conectar ao banco. Verifique os dados!")

?>