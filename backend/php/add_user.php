<?php

    include "utilities/mysql_connect.php";

    $username = $_POST['username'];
    $password = $_POST['password'];
    $acc_type = $_POST['account_type'];

    $query = mysqli_query($connection, "select user_id, username, user_password, account_type, account_status from users where username = '$username'");

    if (!empty(mysqli_fetch_assoc($query))){
        echo '{
            "code": 403,
            "msg": "User already exists"
        }'; //403 Forbidden
    
    }else{
        //TO-DO: Adicionar criptografia para a senha. 

        mysqli_query($connection, "insert into users (username, user_password, account_type) values ('$username','$password','$acc_type')");
        
        echo '{
            "code": 200,
            "msg": "Session found"
        }'; //200 OK

    }

    mysqli_close($connection);

?>