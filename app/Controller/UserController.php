<?php

class UserController
{
    private PDO $pdo;
    public function __construct()
    {
        $this->pdo = new PDO("pgsql:host=db;port=5432;dbname=postgres", "dbuser", "dbpwd");
    }

    public function getRegistrate()
    {
        require_once './../View/registrate.php';
    }
    public function registrate()
    {
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $password = $_POST['psw'];
            $name = $_POST['name'];
            $email = $_POST['email'];

            $password = password_hash($password, PASSWORD_DEFAULT);

            $statement = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $statement->execute(['name' => $name, 'email' => $email, 'password' => $password]);

            header("Location: /login");

        }

        require_once "./../View/registrate.php";
    }

    private function validate(array $data)
    {
        $errors = [];

        $name = $data['name'];
        if (strlen($name) < 2) {
            $errors['name'] = "Имя должо быть больше 2 символов";
        }

        $email = $data['email'];
        if (strlen($email) < 2) {
            $errors['email'] = 'Email должен быть больше 2 символов';
        } else {
            $str = '@';
            $strpos = strpos($email, $str);

            if ($strpos === false) {
                $errors['email'] = 'Email должен содержать @';
            }

        }

        $password = $data['psw'];
        $password_repeat = $data['psw-repeat'];
        if (strlen($password) < 2) {
            $errors['password'] = "Пароль должен быть больше 2 символов";
        } else {

            if ($password != $password_repeat) {
                $errors['password'] = "Пароли не совпадают";
            }
        }

        return $errors;
    }

    public function getLogin()
    {
        require_once './../View/login.php';
    }

    public function login()
    {
        $errors = $this->validateLogin($_POST);

        if (empty($errors)) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $statement = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $statement->execute(['email' => $email]);
            $data = $statement->fetch();

            if (password_verify($password, $data['password'])) {
                session_start();
                $_SESSION['user_id'] = $data['id'];
                header("Location: /main");
            }

        }

        require_once './../View/login.php';
    }

    private function validateLogin(array $arr)
    {
        $errors = [];

        $email = $arr['email'];
        $password = $arr['password'];

        $statement = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $statement->execute(['email' => $email]);
        $data = $statement->fetch();

        if(empty($data)) {
            $errors['email'] = 'Пользователя не существует';
        } elseif (!password_verify($password, $data['password'])) {
            $errors['password'] = "Неверный логин или пароль";
        }

        return $errors;

    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        header('Location: /login');
    }
}