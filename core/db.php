<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "msn";

$link = mysqli_connect($servername, $username, $password, $dbname);

if ($link->connect_error) {
  die("Connection failed: " . $link->connect_error);
}
?>
