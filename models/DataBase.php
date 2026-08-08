<?php
    class DataBase{
        #  Conexión Local
        public static function connection(){
            $hostname = getenv('DB_HOST') ?: "localhost";
            $port = getenv('DB_PORT') ?: "3306";
            $database = getenv('DB_NAME') ?: "db_inventory";
            $username = getenv('DB_USER') ?: "root";

            $password = getenv('MYSQL_SECURE_PASSWORD') !== false ? getenv('MYSQL_SECURE_PASSWORD') : "";

            $pdo = new PDO("mysql:host=$hostname;port=$port;dbname=$database;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
    }
?>