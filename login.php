<?php
require_once('connect.php');
session_start();
$error_msg="";
if(!isset($_SESSION['id'])) {
  if(isset($_POST['submit'])){
    $dbc=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $user_username=mysqli_real_escape_string($dbc,trim($_POST['username']));
    $user_password=mysqli_real_escape_string($dbc,trim($_POST['password']));
     if(!empty($user_username) && !empty($user_password)){
       $query = "SELECT id,username FROM users WHERE username='$user_username' AND password= SHA('$user_password')";
       $data=mysqli_query($dbc,$query) or die(mysqli_error($dbc));
       if(mysqli_num_rows($data)==1){
         $row=mysqli_fetch_array($data);
         $_SESSION['id']=$row['id'];
         $_SESSION['username']=$row['username'];
         setcookie('id',$row['id'],time()+(60*60*24*30));
         setcookie('username',$row['username'],time()+(60*60*24*30));
         $home_url='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
         header('Location: ' . $home_url);
       }
       else{
         $error_msg='Sorry,you must enter a valid username and password to log in.';
       }
     }
     else{
       $error_msg='Sorry, you must enter yourusername and password to log in.';
     }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html"; charset='utf-8'/>
  <title>Central-library login</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <h3>Central-library login</h3>

  <?php
    if(empty($_SESSION['id'])){
      echo '<div class="alert alert-danger">'.$error_msg.'</div>';
  ?>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" width="500px">
    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" class="form-control" name="username">
    </div>
    <div class="form-group">
      <label for="password"> Password:</label>
      <input type="password" class="form-control" name="password">
    </div>
    <button type"submit" class="btn btn_default" name="submit"> Submit</button>
  </form>
<?php
}
else{
  echo('<p class="login">You are logged in as'.$_SESSION['username'].'</p>');
}
?>
</body>
</html>
