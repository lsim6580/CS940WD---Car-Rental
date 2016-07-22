<?php
require_once 'connection.php';
include 'sanitize.php';

if (isset($_POST["type"])) {

    $type = sanitizeMYSQL($connection, $_POST["type"]);
    switch ($type) {
        case "login":

            $username = $_POST["username"];
            $password = $_POST['password'];
            $SQL = "SELECT Customer.ID FROM Customer WHERE Customer.Name='$username' AND Customer.Password='$password'";
            $id = '';
            $result =mysqli_query($connection, $SQL);
            $success = 'Failed';
            if ($result)
                $success =processResult($result);
            echo $success;
            break;
    }
}
function processResult($result){
    setcookie('ID', $result['ID'], time() + 60*60*24*7, '/');
    return 'success';
}
?>