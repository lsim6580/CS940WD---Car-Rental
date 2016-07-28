<?php
require_once 'connection.php';
include 'sanitize.php';
if (isset($_POST["type"])) {
    $type = sanitizeMYSQL($connection, $_POST["type"]);
    switch ($type) {
        case "login":
            $username = sanitizeMYSQL($connection, $_POST['username']);
            $password = md5(sanitizeMYSQL($connection, $_POST['password']));
            $SQL = "SELECT Customer.ID FROM Customer WHERE Customer.Name='" . $username . "' AND Customer.Password='" . $password . "'";
            $result = mysqli_query($connection, $SQL);
            if($result) {
                $row = mysqli_fetch_array($result);
                processResult($row['ID']);
                echo true;
                return;

            }
            echo false;
        break;

    }
}
function processResult($id)
{
    session_start();
    $_SESSION['ID'] = $id;
    $_SESSION['start'] = time();
    ini_set('session.use_only_cookies', 1);


}
