<?php
define("MB", 1048576);

function filterRequest($requestname)
{
    return  htmlspecialchars(strip_tags($_POST[$requestname]));
}

function getAllData($table, $where = null, $values = null,  $json = true)
{
    global $con;
    $data = array();
    if ($where == null) {
        $stmt = $con->prepare("SELECT  * FROM $table ");
    } else {
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    }


    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();

    if ($json) {
        if ($count > 0) {
            printSuccess($data);
        } else {
            printFailure();
        }
        return $count;
    } else {
        if ($count > 0) {
            return $data;
        } else {
            printFailure();
        }
    }
}

function getData($table, $where, $values = null)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($count > 0) {
        printSuccess($data);
    } else {
        printFailure();
    }
    return $count;
}
function getSpeceficData($select = null, $table, $where, $values = null, $json = null)
{
    global $con;
    $data = array();
    if ($select == null) {
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    } else {

        $stmt = $con->prepare("SELECT  $select FROM $table WHERE   $where ");
    }


    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();

    if ($json) {
        if ($count > 0) {
            printSuccess($data);
        } else {
            printFailure();
        }
        return $count;
    } else {
        if ($count > 0) {
            return $data;
        } else {
            printFailure();
        }
    }
}

function insertData($con, $table, $data, $json = true)
{
    try {
        if (empty($data)) {
            throw new Exception("No data provided for insertion.");
        }
        $fields = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));

        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";

        $stmt = $con->prepare($sql);
        foreach ($data as $f => $v) {
            $stmt->bindValue(':' . $f, $v);
        }
        $stmt->execute();

        $count = $stmt->rowCount();

        if ($json) {
            if ($count > 0) {
                echo json_encode(["status" => "success", "message" => "Data inserted successfully.", "inserted_rows" => $count]);
            } else {
                echo json_encode(["status" => "failure", "message" => "No data was inserted."]);
            }
        }

        return $count;
    } catch (PDOException $e) {
        if ($json) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
        return 0;
    } catch (Exception $e) {
        if ($json) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        return 0;
    }
}

function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            printSuccess();
        } else {
            printFailure();
        }
    }
    return $count;
}

function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            printSuccess();
        } else {
            printFailure();
        }
    }
    return $count;
}

function imageUpload($imageRequest)
{
    global $msgError;
    $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
    $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
    $imagesize  = $_FILES[$imageRequest]['size'];
    $allowExt   = array("jpg", "png", "gif", "mp3", "pdf");
    $strToArray = explode(".", $imagename);
    $ext        = end($strToArray);
    $ext        = strtolower($ext);

    if (!empty($imagename) && !in_array($ext, $allowExt)) {
        $msgError = "EXT";
    }
    if ($imagesize > 2 * MB) {
        $msgError = "size";
    }
    if (empty($msgError)) {
        move_uploaded_file($imagetmp,  "../upload/" . $imagename);
        return $imagename;
    } else {
        return "fail";
    }
}



function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "wael" ||  $_SERVER['PHP_AUTH_PW'] != "wael12345") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }
}

function printSuccess($data = null)
{
    if (!empty($data)) {
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        echo json_encode(array("status" => "success"));
    }
}

function printFailure()
{
    echo json_encode(array("status" => "failure"));
}

function handlingResult($count)
{
    if ($count > 0) {
        printSuccess();
    } else {
        printFailure();
    }
}



function sendMail($to, $subject, $message)
{

    $headers = "From: SA3D <anohamed05@gmail.com>\r\n";

    mail($to, $subject, $message, $headers);
}
