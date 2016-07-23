<?php
require_once 'connection.php';
include 'sanitize.php';

if (isset($_POST["type"])) {

    $type = sanitizeMYSQL($connection, $_POST["type"]);
    switch ($type) {
        case "login":
            $username = sanitizeMYSQL($connection, $_POST["username"]);
            $password = md5(sanitizeMYSQL($connection, $_POST['password']));
            $SQL = "SELECT Customer.ID FROM Customer WHERE Customer.Name='$username' AND Customer.Password='$password'";
            $result = mysqli_query($connection, $SQL);
            if (!$result)
                    die("Database access failed: " . mysqli_error());
            $row = mysqli_fetch_array($result);
            echo $row['ID'];
            break;
    }

//
//
//
//            $id = '';
//            $result = mysqli_query($connection, $SQL);
//            echo "HERE";
//            return;
}
function processResult($result){
    setcookie('ID', $result['ID'], time() + 60*60*24*7, '/');
    return 'success';
}
?>