<?php
session_start();

require "functions.php";


$email = $_POST["email"];
$password = $_POST["password"];


$is_not_logged_in = is_not_logged_in($email, $password);


//если не зарегистрирован
if ($is_not_logged_in == true) {
    set_flash_message("danger", "Неправильный email или пароль");
    redirect_to("page_login.php");
}

redirect_to("users.php");
