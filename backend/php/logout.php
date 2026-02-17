<?php

    session_start();

    if(!isset($_SESSION['user_id'])){
        echo '{
            "code": 404,
            "msg": "No session found"
        }';
    
    }else{

        session_destroy();

        echo '{
            "code": 200,
            "msg": "Logged out"
        }';
    }

?>