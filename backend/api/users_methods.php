<?php

    function add_user($request){

        $username = $request -> {'username'};
        $password = $request -> {'password'};
        $acc_type = $request -> {'acc_type'};

        $db = new DatabaseConnection();
        $db -> connect_to_db();

        $response_json = [];

        $user = $db -> query_db("select user_id, username, user_password, account_type, account_status from users where username = '$username'");

        if (!empty(mysqli_fetch_assoc($user))){
            http_response_code(403);
        
            $response_json = [
                "code_type" => "forbidden",
                "msg" => "User already exists"
            ];
    
        }else{

            //TO-DO: Adicionar criptografia para a senha. 

            $db -> query_db("insert into users (username, user_password, account_type) values ('$username','$password','$acc_type')");

            http_response_code(201);

            $response_json = [
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

        $users = $db -> query_db($query_str);

        while($u = mysqli_fetch_assoc($users)){

            array_push($response_json, $u);

        }

        $db -> end_connection();

        if (empty($response_json)){

            http_response_code(404);
            $response_json = [
                "code_type" => "not found",
                "msg" => "No user found"
            ];

        }

        return json_encode($response_json);

    }