<?php

include "connect.php";

$categorie = filterRequest("categorie");
$userId = filterRequest("user_id");

$stmt = $con->prepare("
    SELECT p.*, 
           CASE 
               WHEN f.product_id IS NOT NULL THEN 1 
               ELSE 0 
           END AS is_favorite
    FROM `products` p
    LEFT JOIN `favorite` f ON p.product_id = f.product_id AND f.user_id = ?
    WHERE p.product_categorie = ?
");

$stmt->execute([$userId, $categorie]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count  = $stmt->rowCount();

if ($count > 0) {
    printSuccess($data);
} else {
    printFailure();
}
