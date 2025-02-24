<?php

include "../connect.php";


$email          =    filterRequest("email");
$password       =    sha1(filterRequest("password"));

$stmt = $con->prepare("SELECT * FROM `users` WHERE `user_approve`= 1 and `user_email` = ? and `user_password` = ? ");

$stmt->execute([$email, $password]);


$count = $stmt->rowCount();

handlingResult($count);
