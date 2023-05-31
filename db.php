<?php
function db() {
    static $pdo;

    $server_name = "localhost";
    $db_name = "zwa-proj";
    $username = "root";
    $password = "";

    if ($pdo == null) {
        try {
            $pdo = new PDO("mysql:host=$server_name;dbname=$db_name", $username, $password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    return $pdo;
}
