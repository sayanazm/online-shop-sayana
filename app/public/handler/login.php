<?php

$errors = [];

if (empty($errors)) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $pdo = new PDO("pgsql:host=db;port=5432;dbname=postgres", "dbuser", "dbpwd");

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => $email]);
    $data = $statement->fetch();

    if(empty($data)) {
        $errors['email'] = 'Пользователя не существует';
    } else {
        if (password_verify($password, $data['password'])) {
            session_start();
            $_SESSION['user_id'] = $data['id'];
            header("Location: /main.php");
        } else {
            $errors['password'] = "Неверный логин или пароль";
        }
    }

}