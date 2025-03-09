<?php

include "connect.php";
$allData = array();
$allData['status'] = 'success';
$categories = getAllData("categories", null, null, false);

$allData['categories'] = $categories;
$products = getAllData("products", null, null, false);
$allData['products'] = $products;



echo json_encode($allData);
