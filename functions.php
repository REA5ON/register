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
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");

    //создаем запрос
    $sql = "SELECT * FROM peoples WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    //fetch_assoc формирует ответ из БД в нормальный массив
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // возвращаем $user (то есть весь столбец "email" будет записан в $user)
    return $user;
}


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
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    //Запрос на вставку
    $sql = "INSERT INTO peoples (email, password, role) VALUES (:email, :password, :role)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => $password,
        //создается в БД для каждого нового пользователя статус "user" при регистрации
        "role" => "user",
    ]);

    $_SESSION['id'] = $pdo->lastInsertId();

    return $_SESSION['id'];
}


/*
    Parameters:
            string - $email
            string - $password
    Description: авторизировать пользователя, запись в сессию
    //add_to_session
    Return value: boolean
*/

function is_not_logged_in($email, $password)
{
    $pdo = new PDO("mysql:host=localhost; dbname=new_project", "root", "root");
    $sql = "SELECT * FROM peoples WHERE email=:email AND password=:password";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'email' => $email,
        'password' => $password,
    ]);

    //записываем в массив
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    //проверяем полученый массив
    if (!empty($user) && count($user)) {
        //запись в сессию
        $_SESSION['email'] = $user['email'];
        //$_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['auth'] = true;
        return false;
    } else {
        return true;
    }


}

/*
    Parameters:
            string - $name (ключ)
            string - $message (значение, текст сообщения)

    Description: подготовить флеш сообщение

    Return value: null
*/
function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;
}


/*
    Parameters:
            string - $name (ключ)

    Description: вывести флеш сообщение

    Return value: null
*/
function display_flash_message($name)
{
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
function redirect_to($patch)
{
    header("Location: {$patch}");
    exit;
}


/*
    Parameters: $role
    Description: админ?

    Return value: bool
*/
function is_admin($role)
{
    if ($_SESSION['auth'] = true && $role == 'admin') {
        return true;
    } else {
        return false;
    }
}


//вывод всех пользователей
function get_all_users()
{
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    $sql = "SELECT * FROM peoples";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $people = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $people;
}

/*
    Parameters:
            string - $username
            string - $job_title
            string - $phone
            string - $address
    Description: Редактировать профиль
    Return value: boolean
*/
function edit_information($user_id, $username, $job, $phone, $address)
{
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    $sql = "UPDATE peoples SET (username, job, phone, address) VALUES (:username, :job, :phone, :address) WHERE id='$user_id'";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $user_id,
        'username' => $username,
        'job' => $job,
        'phone' => $phone,
        'address' => $address,
    ]);

}

