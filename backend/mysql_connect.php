<?php

    class DatabaseConnection {
        public $server = "127.0.0.1";
        public $user = "root";
        public $password = "";
        public $database = "bar_do_wood";

        public $connection = null;

        public function use_query($query) {
            $query = mysqli_query($this -> connection, $query);

            return mysqli_fetch_assoc($query);
        }

        public function connect_to_db() {
            $this -> connection = mysqli_connect($this -> server, $this -> user, $this -> password, $this -> database) or die ("Problemas para conectar ao banco. Verifique os dados!");
        }

        public function end_connection() {
            mysqli_close($this -> connection);
        }
        
    }
?>