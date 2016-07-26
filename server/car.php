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
                $cookie = processResult($row['ID']);
                echo true;
                return;

            }
            echo false;
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
            break;
        case "getCars":
            $value = $_POST['value'];
            $SQL = "SELECT * FROM car INNER JOIN carspecs on car.CarSpecsID = carspecs.ID WHERE
            carspecs.Make LIKE '%$value%' OR car.Color LIKE '%$value%' OR carspecs.Model LIKE '%$value%' 
            OR carspecs.YearMade LIKE '%$value%' OR carspecs.Size LIKE '%$value%' AND car.Status = 1";
            $result = mysqli_query($connection, $SQL);
            if($result) {
                $final_result = array();
                $row_count = mysqli_num_rows($result);
                for ($i = 0; $i < $row_count; ++$i) {
                    $row = mysqli_fetch_array($result);
                    $item = array("ID" => $row["ID"], "model" => $row["Model"], 'make' => $row['Make'], 'year' => $row['YearMade'], "size" => $row['Size'],
                        'picture' => 'data:'.$row['Picture_type'].';base64,'.base64_encode($row['Picture']), 'color' => $row['Color'], 'status' => $row['Status']);
                    $final_result[] = $item;

                }
                $it = json_encode($final_result);
                echo $it;

          }
//
            break;
    }
}
function processResult($id){
    session_id($id);
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
//
//
//
