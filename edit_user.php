<?php
session_start();
require "functions.php";


$user_id = $_SESSION['edit_id'];
$username = $_POST['username'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];


edit_information($user_id, $username, $job, $phone, $address);


set_flash_message("success", "Профиль успешно обновлен");


redirect_to("page_profile.php?=$user_id");
