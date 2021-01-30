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


    $user_id = $pdo->lastInsertId();
    return $user_id;

}

/*
    Parameters:
            string - $email
            string - $password
    Description: авторизировать пользователя, запись в сессию
    //add_to_session
    Return value: boolean
*/
function is_not_logged_in($auth) {
    if (!isset($_SESSION['auth'])) {
        return true;
    } else {
        return false;
    }
}


/*
    Parameters:
            string - $email
            string - $password
    Description: авторизировать пользователя, запись в сессию
    //add_to_session
    Return value: boolean
*/

function logged_in($email, $password)
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
    if (empty($user)) {
        set_flash_message("danger", "Неправильный email или пароль");
        return false;
    }


    //запись в сессию
    $_SESSION['id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['auth'] = true;
    return true;
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
function is_admin()
{
     if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
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
    $sql = "UPDATE peoples SET username=:username, job=:job, phone=:phone, address=:address WHERE id='$user_id'";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'username' => $username,
        'job' => $job,
        'phone' => $phone,
        'address' => $address,
    ]);
}

/*
    Parameters:
            string - $status
    Description:  Установить статус
    Return value: NULL
*/
function set_status($user_id, $status) {
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    $sql = "UPDATE peoples SET status=:status WHERE id='$user_id'";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'status' => $status,
    ]);
}

/*
    Parameters:
            string - $image
    Description:  Загрузить аватар
    Return value: NULL / string(path)
*/
function upload_avatar($user_id, $image) {
    $name = $image['name'];
    //PATHINFO_EXTENSION - возвращает расширение файла
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $path = 'img/demo/avatars/';


    //уникальное имя
    $new_name = uniqid();
    //склеиваем
    $file = $path . $new_name . '.' . $ext;
    //move_uploaded_file(откуда, куда);
    move_uploaded_file($image['tmp_name'], $file);

    //Записываем в БД
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    $sql = "UPDATE peoples SET image='$file' WHERE id='$user_id'";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'image' => $file,
    ]);
}

/*
    Parameters:
            string - $user_id,
            string - $vk,
            string - $telegram,
            string - $instagram
    Description:  Добавить социальные сети
    Return value: -
*/
function add_social_links($user_id, $vk, $telegram, $instagram) {
    //Подключаемся к БД
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    //Запрос на вставку
    $sql = "UPDATE peoples SET vk=:vk, telegram=:telegram, instagram=:instagram WHERE id='$user_id'";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "vk" => $vk,
        "telegram" => $telegram,
        'instagram' => $instagram,
    ]);
}

/*
    Parameters:
            int - $logged_user_id,
            int - $edit_user_id
    Description:  проверить автор ли текущий пользователь
    Return value: boolean
*/
function is_author($logged_user_id, $edit_user_id) {
    if ($logged_user_id == $edit_user_id) {
        return true;
    } else {
        return false;
    }
}


/*
    Parameters:
            int - $user_id,
    Description:  получить пользователя по id
    Return value: array
*/
function get_user_by_id($id) {
    //подключаемся к БД
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");

    //создаем запрос
    $sql = "SELECT * FROM peoples WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id" => $id]);
    //fetch_assoc формирует ответ из БД в нормальный массив
    $get_id = $statement->fetch(PDO::FETCH_ASSOC);

    return $get_id;
}

/*
    Parameters:
            int - $user_id,
            string - $email
            string - $password
    Description:  Редактировать входные данные: email or password
    Return value: null | boolean
*/

function edit_credentials($user_id, $email, $password) {
    //Подключаемся к БД
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    //Запрос на бновление
    $sql = "UPDATE peoples SET email=:email, password=:password WHERE id='$user_id'";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        "email" => $email,
        "password" => $password,
    ]);
}

/*
    Parameters:
            int - $user_id,
            string - $image
    Description:  Проверяет имеется ли аватар у пользователя
    Return value: null | boolean
*/
function has_image($user_id, $image) {
    if (!empty($user_id['image'])) {
        echo $user_id['image'];
    } else {
        echo $image;
    }
}


function delete($user_id) {
    $pdo = new PDO("mysql:host=localhost;dbname=new_project", "root", "root");
    //Запрос на бновление
    $sql = "DELETE FROM peoples WHERE id='$user_id'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
}


