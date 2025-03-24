<?php
include "connect.php";

$user = filterRequest("user_id");
$product = filterRequest("product_id");

$stmt = $con->prepare("SELECT 1 FROM users WHERE user_id = ? AND EXISTS (SELECT 1 FROM products WHERE product_id = ?)");

$stmt->execute([$user, $product]);
$count = $stmt->rowCount();

if ($count > 0) {
    $data = [
        "user_id" => $user,
        "product_id" => $product,
    ];
    insertData($con, "favorite", $data, true);
} else {
    printFailure();
}
