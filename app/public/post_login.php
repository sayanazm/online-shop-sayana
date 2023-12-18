<?php

$errors = [];

if (empty($errors)) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $pdo = new PDO("pgsql:host=db;port=5432;dbname=postgres", "dbuser", "dbpwd");

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => $email]);
    $data = $statement->fetch();

    if (password_verify($password, $data['password'])) {
        session_start();
        $_SESSION['user_id'] = $data['id'];
    }

}