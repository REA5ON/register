<?php
session_start();

require "functions.php";


$email = $_POST["email"];
$password = $_POST["password"];



$is_not_logged_in = is_not_logged_in($email, $password);

$is_admin = is_admin();


//если не зарегистрирован
if ($is_not_logged_in == true) {
    set_flash_message("danger", "Неправильный email или пароль");
    redirect_to("page_login.php");
}

if ($is_admin == true) {
    $_SESSION['admin_add_button'] = "<a class=\"btn btn-success\" href=\"create_user.php\">Добавить</a>";
}


redirect_to("users.php");
