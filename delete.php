<?php
session_start();


require "functions.php";


$auth = $_SESSION['auth'];
$logged_user_id = $_SESSION['id'];
$edit_user_id = $_GET['id'];
$null_image ='img/demo/avatars/null_image.png';


$is_not_logged_in = is_not_logged_in($auth);

$is_admin = is_admin();

$is_author = is_author($logged_user_id, $edit_user_id);


$get_id = get_user_by_id($edit_user_id);


if ($is_not_logged_in == true) {
    redirect_to("page_login.php");
}

//Если не админ и не автор
if ($is_admin == false && $is_author == false) {
    set_flash_message("danger", "Можно редактировать только свой профиль!");
    redirect_to("users.php");
}

//Записываем/обновляем в сессию id полученый с GET параметром
$_SESSION['edit_id'] = $_GET['id'];


delete($_SESSION['edit_id']);

set_flash_message("success", "Пользователь удален");

if ($is_author == true) {
    session_destroy();
    redirect_to("page_register.php");
}

redirect_to("users.php");


