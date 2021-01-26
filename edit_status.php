<?php
session_start();
require "functions.php";

$user_id = $_SESSION['edit_id'];
$status = $_POST['status'];


set_status($user_id, $status);

set_flash_message("success", "Профиль успешно обновлен");

redirect_to("page_profile.php?id=$user_id");