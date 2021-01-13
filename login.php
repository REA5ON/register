<?php
session_start();
require "functions.php";


$email = $_POST["email"];
$password = $_POST["password"];


$logged_in = logged_in($email, $password);


//если не зарегистрирован
if ($logged_in == false) {
    redirect_to("page_login.php");
}


//перенаправляем
redirect_to("users.php");
