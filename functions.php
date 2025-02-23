<?php
define("MB", 1048576);

function filterRequest($requestname)
{
    return  htmlspecialchars(strip_tags($_POST[$requestname]));
}

function getAllData($table, $where, $values = null)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($count > 0) {
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    return $count;
}

function insertData($con, $table, $data, $json = true)
{
    try {
        // التحقق من أن البيانات غير فارغة
        if (empty($data)) {
            throw new Exception("No data provided for insertion.");
        }

        // تحضير أسماء الأعمدة والقيم
        $fields = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));

        // إنشاء الاستعلام SQL
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";

        // تحضير وتنفيذ الاستعلام
        $stmt = $con->prepare($sql);
        foreach ($data as $f => $v) {
            $stmt->bindValue(':' . $f, $v);
        }
        $stmt->execute();

        // التحقق من عدد الصفوف المتأثرة
        $count = $stmt->rowCount();

        // إرجاع النتيجة كـ JSON إذا كان المطلوب
        if ($json) {
            if ($count > 0) {
                echo json_encode(["status" => "success", "message" => "Data inserted successfully.", "inserted_rows" => $count]);
            } else {
                echo json_encode(["status" => "failure", "message" => "No data was inserted."]);
            }
        }

        // إرجاع عدد الصفوف المتأثرة
        return $count;
    } catch (PDOException $e) {
        // معالجة أخطاء PDO
        if ($json) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
        return 0;
    } catch (Exception $e) {
        // معالجة الأخطاء العامة
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
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
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
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
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

function printFailure()
{
    echo json_encode(array("status" => "failure"));
}

function sendMail($to, $subject, $message)
{

    $headers = "From: SA3D <anohamed05@gmail.com>\r\n";
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}
