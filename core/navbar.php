<?php
// Initialize the session

// Check if the user is logged in, if not then redirect him to login page

?>
<link rel="stylesheet" href="bootstrap.css">
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><img src="images/msnblox.ico" style=" height: 25px; width: 33px;"></a>
      <a class="navbar-brand" href="#">MSN</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Games <span class="sr-only">(current)</span></a></li>
        <li><a href="#">Catalog</a></li>
        <li><a href="#">Forums</a></li>
        <li><a href="#">Users</a></li>
       
      </ul>
      
      <ul class="nav navbar-nav navbar-right" >
       
        <li> <td align="right" class="nav_sub">
            <div style="margin-top: 15px;">     <p>   <?php if(isset($_SESSION['username'])): ?>
                        Welcome, <b><?= htmlspecialchars($_SESSION['username']) ?></b> |
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a> | <a href="Sign Up.php">Register</a>
                    <?php endif; ?> </p>
                </td> </li>

      </ul>
    </div>
  </div>
</nav>