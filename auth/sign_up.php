<?php

include "../connect.php";


$userName = filterRequest("username");
$email = filterRequest("email");
$phone = filterRequest("phone");
$password = sha1("password");
$verify_code = rand(10000,99999);

$stmt = $con->prepare("SELECT * FROM `users` WHERE

                        `user_email` = ?
                        OR
                        `user_phone` = ?
                                                        ");

$stmt ->execute([$email, $phone]);


$count = $stmt->rowCount();

if ($count >0) {
    printFailure();
}else{
    $data =[
        "user_name" => $userName,
        "user_email" => $email,
        "user_phone" => $phone,
        "user_password" => $password,
        "user_verify_code" => $verify_code,
          ];
        sendMail($email, "Verify your email", "Your verification code is: $verify_code");
    insertData($con,"users", $data);
}






?>
