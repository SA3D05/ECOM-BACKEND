<?php

include "connect.php";
$allData = array();
$allData['status'] = 'success';
$categories = getAllData("categories", null, null, false);

$allData['categories'] = $categories;

echo json_encode($allData);
