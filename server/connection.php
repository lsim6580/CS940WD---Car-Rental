
<?php

  $db_hostname = 'kc-sce-appdb01';
  $db_database = "jronq5";
  $db_username = "jronq5";
  $db_password = "QwiLHWIZix4uJ9qrpNlz";

/*$db_hostname = 'localhost';
$db_database = "project3";
$db_username = "root";
$db_password = "";*/


$connection = mysqli_connect($db_hostname, $db_username,$db_password,$db_database);

if (!$connection)
    die("Unable to connect to MySQL: ");

?>