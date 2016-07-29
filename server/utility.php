<?php
require_once 'connection.php';
include 'sanitize.php';

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

            $SQL = "SELECT car.ID, car.Picture,car.Picture_type,car.Color,car.Status,carspecs.Make,carspecs.Size,
                    carspecs.YearMade,carspecs.Model FROM car INNER JOIN carspecs ON car.CarSpecsID = carspecs.ID
                    WHERE $LIKE AND car.Status = 1";
//            $SQL = "SELECT * FROM car INNER JOIN carspecs on car.CarSpecsID = carspecs.ID WHERE
//            $LIKE AND car.Status = 1";

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

        case 'getRentals':
            session_start();
            $userID = $_SESSION["ID"];
            $SQL = "SELECT car.ID as 'carID', car.Picture, car.Picture_type, carspecs.Make, carspecs.Model, carspecs.YearMade, carspecs.Size, rental.ID as 'RentID', rental.rentDate FROM car
                INNER JOIN carspecs on car.CarSpecsID = carspecs.ID
                INNER JOIN rental on car.ID = rental.carID WHERE
                car.Status = 2 AND Rental.CustomerID = '".$userID."'";

            $result = mysqli_query($connection, $SQL);
            if($result) {
                $final_result = array();
                $row_count = mysqli_num_rows($result);
                for ($i = 0; $i < $row_count; ++$i) {
                    $row = mysqli_fetch_array($result);
                    $item = array("model" => $row["Model"], 'make' => $row['Make'], 'year' => $row['YearMade'], "size" => $row['Size'],
                        'picture' => 'data:'.$row['Picture_type'].';base64,'.base64_encode($row['Picture']), 'rent_date' => $row['rentDate'],
                        'car_ID' => $row['carID']);
                    $final_result[] = $item;

                }
                $it = json_encode($final_result);
                echo $it;
//
         }
//
            break;

        case 'rentCar':
            $value = $_POST['value'];

            session_start();
            $userID = $_SESSION["ID"];
            
            //$Update = "UPDATE car SET car.Status = 2 WHERE ID = $value;";
           // mysqli_query($connection, $Update);
            
            $date = getdate();
            $Insert = "INSERT INTO rental(rentDate, status, CustomerID, carID)";
            $Insert.="VALUES('".$date['year']."-".$date['mon']."-".$date['mday']."',";
            $Insert.="2, '$userID', '$value');";
           // mysqli_query($connection, $Insert);
            echo $Insert;
            break;

        case 'getReturns':
            session_start();
            $userID = $_SESSION["ID"];
            $SQL = "select rental.ID, rental.returnDate, carspecs.Make, carspecs.Model, carspecs.Size, carspecs.YearMade, car.Picture, car.Picture_type FROM rental";
            $SQL .= "INNER JOIN car on rental.carID = car.ID";
            $SQL .= "INNER JOIN carspecs on car.carSpecsID = carspecs.ID";
            $SQL .= "WHERE rental.CustomerID = '$userID' AND rental.status = 1;";

            $result = mysqli_query($connection, $SQL);

            if($result) {
                $final_result = array();
                $row_count = mysqli_num_rows($result);
                for ($i = 0; $i < $row_count; ++$i) {
                    $row = mysqli_fetch_array($result);
                    $item = array("model" => $row["Model"], 'make' => $row['Make'], 'year' => $row['YearMade'], "size" => $row['Size'],
                        'picture' => 'data:'.$row['Picture_type'].';base64,'.base64_encode($row['Picture']), 'return_date' => $row['returnDate'],
                        'rental_ID' => $row['ID']);
                    $final_result[] = $item;

                }
                $it = json_encode($final_result);
                echo $it;
//
            }
//
            break;

            case 'returnCar':
                session_start();
                $userID = $_SESSION["ID"];
                $value = ['value'];

                $UpdateCar = "UPDATE car SET car.Status = 1 WHERE ID = $value;";
                mysqli_query($connection, $Update);
                $date = getdate();
                $UpdateRental = "UPDATE rental set rental.status = 1, rental.returnDate = ".$date['year']."-".$date['mon']."-".$date['mday']
                        ." WHERE carID = '$value' AND CustomerID = '$userID';";
                mysqli_query($connection, $UpdateRental);
                echo true;
                break;


            case 'getName':
                session_start();
                $SQL = "Select Name FROM customer WHERE Customer.ID = '".$_SESSION['ID']."'";
                $result = mysqli_query($connection, $SQL);
                $row  = mysqli_fetch_array($result);;
                echo $row['Name'];
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

