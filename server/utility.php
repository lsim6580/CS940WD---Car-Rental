<?php
require_once 'connection.php';
include 'sanitize.php';
$SQL =  "SELECT Customer.ID FROM Customer WHERE Customer.Name='John Smith' AND Customer.Password='".md5('smith')."'";
$result = mysqli_query($connection,$SQL);
$count = $result->fetch_array();
if (isset($_POST["type"])) {
    $type = sanitizeMYSQL($connection, $_POST["type"]);
    switch ($type) {
        case "logout":
            logout();
            echo true;
            break;
        case "getCars":
            $value = $_POST['value'];
            $words = explode(' ', $value);
            $LIKE = '';
            $LIKE.=LIKE("carspecs.Make", $words);
            $LIKE.=" OR " . LIKE("car.Color", $words);
            $LIKE.=" OR " . LIKE("carspecs.Model", $words);
            $LIKE.=" OR " . LIKE("carspecs.YearMade", $words);
            $LIKE.=" OR " . LIKE("carspecs.Size", $words);

            $SQL = "SELECT * FROM car INNER JOIN carspecs on car.CarSpecsID = carspecs.ID WHERE
            $LIKE AND car.Status = 1";

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
//
            }
//
        break;
        case 'getName':
            session_start();
            $SQL = "Select Name FROM customer WHERE Customer.ID = '".$_SESSION['ID']."'";
            $result = mysqli_query($connection, $SQL);
            $row  = mysqli_fetch_array($result);;
            echo $row['Name'];
            return;
            //        logout();
            // processResult('j.smith');

            break;

//
    }
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

function LIKE($column, $words) {
    $LIKE = "";
    $index = 0;
    foreach ($words as $word) {
        if ($index > 0)
            $LIKE.=" OR ";

        $LIKE.=" $column LIKE '%$word%' ";
        $index++;
    }
    return $LIKE;
}
//
