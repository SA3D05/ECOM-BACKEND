<?php
include "../../connect.php";
$email = filterRequest("email");
$code = filterRequest("verify_code");

$stmt = $con->prepare("SELECT * FROM `users` WHERE `user_email` = ? AND `user_verify_code` = ? AND `user_approve` = 1 ");


$stmt->execute([$email, $code]);

$count = $stmt->rowCount();

handlingResult($count);
