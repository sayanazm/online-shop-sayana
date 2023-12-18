<?php

$errors = [];

$name = $_POST['name'];

    if (strlen($name) < 2) {
        $errors['name'] = "Имя должо быть больше 2 символов";
    }

$email = $_POST['email'];

    if (strlen($email) < 2) {
        $errors['email'] = 'Email должен быть больше 2 символов';
    } else {
        $str = '@';
        $strpos = strpos($email, $str);

        if ($strpos === false) {
            $errors['email'] = 'Email должен содержать @';
        }

    }

$password = $_POST['psw'];
$password_repeat = $_POST['psw-repeat'];

    if (strlen($password) < 2) {
        $errors['password'] = "Пароль должен быть больше 2 символов";
    } else {

        if ($password != $password_repeat) {
            $errors['password'] = "Пароли не совпадают";
        }
    }
$password = password_hash($password, PASSWORD_DEFAULT);

    if (empty($errors)) {
        $pdo = new PDO("pgsql:host=db;port=5432;dbname=postgres", "dbuser", "dbpwd");
        $statement = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $statement->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        require_once "./get_login.php";
//        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
//        $statement->execute(['email' => $email]);
//        $data = $statement->fetch(PDO::FETCH_ASSOC);
//        print_r($data);
    } else {
        require_once "./get_registrate.php";
    }

?>

