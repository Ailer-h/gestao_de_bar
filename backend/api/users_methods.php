<?php

    function add_user($request){

        $username = $request -> {'username'};
        $password = $request -> {'password'};
        $acc_type = $request -> {'acc_type'};

        $db = new DatabaseConnection();
        $db -> connect_to_db();

        $response_json = [];

        $user = $db -> use_query("select user_id, username, user_password, account_type, account_status from users where username = '$username'");

        if (!empty(mysqli_fetch_assoc($user))){
            $response_json = [
                "code" => 403,
                "code_type" => "forbidden",
                "msg" => "User already exists"
            ];
    
        }else{

            //TO-DO: Adicionar criptografia para a senha. 

            $db -> use_query("insert into users (username, user_password, account_type) values ('$username','$password','$acc_type')");

            $response_json = [
                "code" => 200,
                "code_type" => "created",
                "msg" => "User added"
            ];

        }

        $db -> end_connection();

        return json_encode($response_json);

    }

    function get_users($user_id = null) {

        $db = new DatabaseConnection();
        $db -> connect_to_db();

        $response_json = [];

        $query_str = "select user_id, username, user_password, account_type, account_status, creation_date from users";

        if ($user_id !== null){

            $query_str = "select user_id, username, user_password, account_type, account_status, creation_date from users where user_id = $user_id";
            
        }

        $users = $db -> use_query($query_str);

        while($u = mysqli_fetch_assoc($users)){

            array_push($response_json, $u);

        }

        $db -> end_connection();

        return json_encode($response_json);

    }