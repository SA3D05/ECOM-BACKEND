<?php

include "connect.php";

$user = $_GET["user_id"];


getAllData("favorite", "user_id = ?", [$user], true);
