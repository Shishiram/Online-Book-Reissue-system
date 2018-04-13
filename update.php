<?php
  session_start();
  require_once('appvars.php');
  require_once('connect.php');
  // If the session vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['id'])) {
    if (isset($_COOKIE['id']) && isset($_COOKIE['username'])) {
      $_SESSION['id'] = $_COOKIE['id'];
      $_SESSION['username'] = $_COOKIE['username'];
    }
  }
  if(isset($_POST['submit'])){
    $iSecsInDay = 86400;
    $iTotalDay = 30;
    $user_signup = time() + ($iSecsInDay * $iTotalDay);
    $dbc=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    //echo $user_signup;
    $newdate = date('y-m-d', $user_signup);
    $query = "UPDATE books SET date_posted = '".$newdate."' WHERE id = '". $_SESSION['id'] ."' AND book_name='".$_POST['book']."'";
    mysqli_query($dbc,$query);
    $home_url='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/viewbooks.php';
    header('Location: ' . $home_url);
  }
?>
