<?php
session_start();

require "functions.php";


$email = $_POST["email"];
$password = $_POST["password"];



$user = logged_in($email, $password);


if ($user == false) {
    set_flash_message("danger", "Неправильный email или пароль");
    redirect_to("page_login.php");
}



//если не зарегистрирован
if (is_not_logged_in()) {
    redirect_to("page_login.php");
}


redirect_to("users.php");

get_all_users();


















//set_flash_message("success", "Добро пожаловать, " . $_SESSION['email'] . "!");
//redirect_to("users.php");