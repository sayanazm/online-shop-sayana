<?php
print_r($_POST);

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['psw'];

$pdo = new PDO("pgsql:host=db;port=5432;dbname=postgres", "dbuser", "dbpwd");
print_r($pdo);
$pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");