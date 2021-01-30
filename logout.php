<?php

session_start();
require "functions.php";

session_destroy();

redirect_to("page_login.php");
