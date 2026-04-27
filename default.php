<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
include 'core/navbar.php'
?>
<center><div class="alert alert-dismissible alert-warning" style="width: 1000px; text-align: left;">
  
  <h4>Warning!</h4>
  <p>This is incredibly W.I.P <a href="#" class="alert-link">some stuff doesnt work</a>.</p>
</div></center>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="bootstrap.css">
    <style>
       
    </style>
</head>
<body>
    <center><h1 class="my-5"> <img src="images/msnblox.ico" style="height: 150px; width: 200px;">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to MSN.</h1></center>
  
</body>
</html>