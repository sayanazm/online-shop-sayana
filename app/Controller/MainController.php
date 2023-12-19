<?php

class MainController
{
    public function getProducts()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }

        $pdo = new PDO("pgsql:host=db;port=5432;dbname=postgres", "dbuser", "dbpwd");

        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchALL();

        require_once "./../View/main.php";
    }

}