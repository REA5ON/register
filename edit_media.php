<?php
session_start();
require "functions.php";

$image = $_FILES['avatar'];
$user_id = $_SESSION['edit_id'];

upload_avatar($user_id, $image);

set_flash_message("success", "Профиль успешно обновлен");

redirect_to("page_profile.php?id=$user_id");