<?php

include "../connect.php";


$email          =    filterRequest("email");
$password       =    sha1(filterRequest("password"));

getData("users", "`user_approve`= 1 and `user_email` = ? and `user_password` = ?", [$email, $password]);
