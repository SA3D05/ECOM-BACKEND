<?php 
include "../connect.php";
$email = filterRequest("email");
$code = filterRequest("verify_code");

$stmt = $con->prepare("SELECT * FROM `users` WHERE `user_email` = ? AND `user_verify_code` = ?");


$stmt->execute([$email, $code]);

$count = $stmt->rowCount();

if ($count >0) {

    
    $data =["user_approve"=>1];
    
    updateData("users",$data,"`user_email` = '$email'");
}
else{
    printFailure();
}




?>