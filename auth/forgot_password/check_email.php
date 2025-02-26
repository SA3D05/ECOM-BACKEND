<?php

include "../../connect.php";
$email          =    filterRequest("email");
$verify_code    =    rand(10000, 99999);
$stmt = $con->prepare("SELECT * FROM `users` WHERE `user_approve`= 1 and `user_email` = ?");

$stmt->execute([$email]);
$count = $stmt->rowCount();

if ($count > 0) {
    $data = [

        "user_verify_code" => $verify_code,
    ];
    updateData("users", $data, " `user_email` = '$email' ");
    sendMail($email, "Verify your email for reset your password", "Your verification code is: $verify_code");
} else {
    printFailure();
}
