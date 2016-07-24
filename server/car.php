<?php
require_once 'connection.php';
include 'sanitize.php';
//$SQL =  "SELECT Customer.ID FROM Customer WHERE Customer.Name='John Smith' AND Customer.Password='".md5('smith')."'";
// $result = mysqli_query($connection,$SQL);
//$count = $result->fetch_array();
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
                $cookie = processResult();
                echo 'success';
                return;

            }
            echo 'fail';
        break;
        case "authenticate":
            if(is_session_active()){
                echo true;
                return;
            }
            else echo false;
            break;
        case "logout":
            logout();
            echo true;
    }
}
function processResult(){
    session_start();
    $_SESSION['start']= time();

}
function logout(){
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function is_session_active() {
    return isset($_SESSION) && count($_SESSION) > 0 && time() < $_SESSION['start']+60*5;
}



