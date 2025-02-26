<?php
include "../../connect.php";
$email          =    filterRequest("email");
$pass           =    filterRequest("password");
$password       =    sha1($pass);



$stmt = $con->prepare("SELECT `user_password` FROM `users` WHERE `user_email` = ? ");
$stmt->execute([$email]);
$old_pass = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($pass) || $old_pass == $password) {
    printFailure();
} else {
    $data = [
        "user_password" => $password
    ];
    updateData("users", $data, "`user_email`= '$email' ");
}
