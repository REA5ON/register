<?php
session_start();

require "functions.php";


$email = $_POST["email"];
$password = $_POST["password"];

$username = $_POST['username'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$status = $_POST['status'];
$image = $_FILES['avatar'];

$vk = $_POST['vk'];
$telegram = $_POST['telegram'];
$instagram = $_POST['instagram'];




$user = get_user_by_email($email);
//если занят имейл - выводим сообщение и перенаправляем обратно на страницу
if (!empty($user)) {
    //Выводим сообщение
    set_flash_message("danger",
        "<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.");
    //перенаправляем
    redirect_to("create_user.php");
}


//если не занят имейл - добавляем пользователя
$user_id = add_user($email, $password);


//редактируем
edit_information($user_id, $username, $job, $phone, $address);

//статус
set_status($user_id, $status);

//аватар
upload_avatar($user_id, $image);

//социальные сети
add_social_links($user_id, $vk, $telegram, $instagram);

//формируем сообщение
set_flash_message("success", "Новый пользователь успешно создан!");

//перенаправляем
redirect_to("users.php");