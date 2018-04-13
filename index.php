<?php
  session_start();

  // If the session vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['id'])) {
    if (isset($_COOKIE['id']) && isset($_COOKIE['username'])) {
      $_SESSION['id'] = $_COOKIE['id'];
      $_SESSION['username'] = $_COOKIE['username'];
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<?php
  require_once('appvars.php');
  require_once('connect.php');
  echo'<img class="img-responsive" src="nitwlogo.png" alt="library photo" width="1300" height="100" margin="100">';
  // Generate the navigation menu
  if (isset($_SESSION['username'])){
    echo '<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <img class="navbar-brand" src="nitwlogo1.png" alt="logo photo" width="50" height="50">
        <a class="navbar-brand" href="#">Central-Library</a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="viewbooks.php">View Books</a></li>
        <li><a href="logout.php">Log Out (' . $_SESSION['username'] . ')</a></li>
      </ul>
    </div>
    </nav>';
  }
  else {
    echo '<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <img class="navbar-brand" src="nitwlogo1.png" alt="logi photo" width="50" height="50">
        <a class="navbar-brand" href="#">Central-Library</a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <li><a href="signup1.php"><span class="glyphicon glyphicon-user"></span> Admin-Sign Up</a></li>
        <li><a href="login1.php"><span class="glyphicon glyphicon-log-in"></span> Admin-Login</a></li>
    </ul>
    </div>
    </nav>';
  }

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Retrieve the user data from MySQL
  $query = "SELECT id, first_name, picture FROM users WHERE first_name IS NOT NULL ORDER BY join_date DESC LIMIT 5";
  $data = mysqli_query($dbc, $query);
  //echo'<body style="background-image:url('lib.JPG')">
    //    <h2>Background Image</h2>
      //</body>';
  echo'<img class="img-responsive" src="lib.JPG" alt="library photo" width="1350" height="700">';

  mysqli_close($dbc);
?>

</body>
</html>
