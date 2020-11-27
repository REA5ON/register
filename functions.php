<?php

/*
    Parameters:
            string - $email
    Description: поиск пользователя по эл. адресу

    Return value: array
*/

function get_user_by_email($email)
{
    //подключаемся к БД
    $pdo = new PDO("mysql:host=localhost;dbname=project_registration", "root", "root");

    //создаем запрос
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // возвращаем $user (то есть весь столбец "email" будет записан в $user)
    return $user;
};


/*
    Parameters:
            string - $email
            string - $password
    Description: Добавить пользователя в БД

    Return value: int (user_id)
*/
function add_user($email, $password)
{
    //Подключаемся к БД
    $pdo = new PDO("mysql:host=localhost;dbname=project_registration", "root", "root");
    //Запрос на вставку
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => $password
    ]);

    return $pdo->lastInsertId();
};

/*
    Parameters:
            string - $email
            string - $password
    Description: авторизировать пользователя

    Return value: boolean
*/

function login($email, $password)
{
    $pdo = new PDO("mysql:host=localhost; dbname=project_registration", "root", "root");
    $sql = "SELECT * FROM users WHERE email=:email AND password=:password";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'email' => $email,
        'password' => $password
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

//is_array - Определяет, является ли переменная массивом
//count - Подсчитывает количество элементов массива или чего-либо в объекте
    if (is_array($user) && count($user)) {
        return true;
    }
    else {
        return false;
    }

}

/*
    Parameters:
            string - $name (ключ)
            string - $message (значение, текст сообщения)

    Description: подготовить флеш сообщение

    Return value: null
*/
function set_flash_message($name, $message) {
    $_SESSION[$name] = $message;
}


/*
    Parameters:
            string - $name (ключ)

    Description: вывести флеш сообщение

    Return value: null
*/
function display_flash_message($name) {
    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    }
}


/*
    Parameters:
            string - $patch (путь)

    Description: перенаправить пользователя

    Return value: null
*/
function redirect_to($patch) {
    header("Location: {$patch}");
    exit;
}
