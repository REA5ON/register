<?php
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];
$user_id = $_SESSION['edit_id'];


$user = get_user_by_email($email);


//Если не пустое значение вернула бд ИЛИ введенный имейл не совпадает с редактируемым пользователем
if (!empty($user) && $email != $_SESSION['edit_email']) {
    set_flash_message("danger", "Email занят");
    redirect_to("security.php?id=$user_id");
}

edit_credentials($user_id, $email, $password);

set_flash_message("success", "Профиль успешно обновлен");

redirect_to("page_profile.php?id=$user_id");

