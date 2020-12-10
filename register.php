<?php
//подключаем сессию
session_start();
//импортируем функции
require "functions.php";


//определяем переменные в сесии
$email = $_POST["email"];
$password = $_POST["password"];


$user = get_user_by_email($email);


//проверяем
if (!empty($user)) {
    //Выводим сообщение
    set_flash_message("danger",
        "<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.");
    //перенаправляем
    redirect_to("page_register.php");
}

//Добавляем пользователя в БД
add_user($email, $password);
//Выводим сообщение
set_flash_message("success", "Регистрация успешна");
//перенаправляем
redirect_to("page_login.php");


