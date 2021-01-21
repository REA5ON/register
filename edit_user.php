<?php
session_start();
require "functions.php";

//var_dump($_GET);die;
$id = $_GET['id'];
$user_id = get_user_by_id($id);
$username = $_POST['username'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];

//var_dump($_POST);die;
edit_information($user_id, $username, $job, $phone, $address);


set_flash_message("success", "Профиль успешно обновлен");


redirect_to("page_profile.php");
