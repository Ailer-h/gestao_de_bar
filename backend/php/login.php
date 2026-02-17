<?php

    include "utilities/mysql_connect.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($connection, "select user_id, username, user_password, account_type, account_status from users where username = '$username'");
    $user = mysqli_fetch_assoc($query);

    if (empty($user)){
        echo '{
            "code": 404,
            "msg": "User not found"
        }';

    }else if ($user['account_status'] == 'inactive'){
        echo '{
            "code": 403,
            "msg": "Inactive account"
        }'; //403 Forbidden
    
    }else if ($user['user_password'] != $password){
        echo '{
            "code": 401,
            "msg": "Incorrect password"
        }'; //401 Unauthorized
    
    }else {

        session_start();

        $_SESSION["username"] = $user["username"];
        $_SESSION["account_type"] = $user["account_type"];
        $_SESSION["user_id"] = $user["user_id"];

        echo '{
            "code": 200,
            "msg": "Login successful"
        }'; //200 OK
    }

    mysqli_close($connection);

?>