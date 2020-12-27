<?php
session_start();

require "functions.php";


$email = $_POST["email"];
$password = $_POST["password"];
//$role = $_SESSION['role'];



$is_not_logged_in = is_not_logged_in($email, $password);
//$is_admin = is_admin($role);

//если не зарегистрирован и не админ - перенаправляем на страницу входа
//if ($is_not_logged_in == true && $is_admin == false) {
//    redirect_to("page_login.php");
//}



$user = get_user_by_email($email);
//если занят имейл - выводим сообщение и перенаправляем обратно на страницу
if (!empty($user)) {
    //Выводим сообщение
    set_flash_message("danger",
        "<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.");
    //перенаправляем
    redirect_to("create_user.php");
}


//если пустой имейл - добавляем пользователя
add_user($email, $password);


$user_id = $_SESSION['id'];
$username = $_POST['username'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];


edit_information($user_id, $username, $job, $phone,$address);

