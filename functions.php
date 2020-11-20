<?php
function get_user_by_email($email)
{
    //подключаемся к БД
    $pdo = new PDO("mysql:host=localhost;dbname=project_registration", "root", "root");

    //подключаемся к таблице для проверки пользователя по email (зарегистрирован или нет)
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    //получаем данные в переменную $user
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // возвращаем $user (то есть весь столбец "email" будет записан в $user)
    return $user;
};



function add_user($email, $password)
{
    //Подключаемся к БД
    $pdo = new PDO("mysql:host=localhost;dbname=project_registration", "root", "root");
    //Запрос на вставку
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);

    return $pdo->lastInsertId();
};

function set_flash_message($name, $message) {
    $_SESSION[$name] = $message;
}


function redirect_to($patch) {
    header("Location: {$patch}");
    exit;
}

function display_flash_message($name) {
    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    }
}