<?php
session_start();

require "functions.php";


$email = $_POST["email"];
$password = $_POST["password"];



$user = login($email, $password);


if ($user == false) {
    set_flash_message("danger", "Неправильные email и пароль");
    redirect_to("page_login.php");
}


set_flash_message("success", "Добро пожаловать {$email}!");
redirect_to("users.php");