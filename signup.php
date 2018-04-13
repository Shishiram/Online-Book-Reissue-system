<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <title>Central-library Signup</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Central-Library Signup</h3>
<?php
  require_once('appvars.php');
  require_once('connect.php');

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $id = mysqli_real_escape_string($dbc, trim($_POST['id']));
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

    if (!empty($id) && !empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
      //echo (strlen($password1)).'</div>';
      // Make sure someone isn't already registered using this username
      if((strlen($password1))>8){
        $query = "SELECT * FROM users WHERE id='$id'";
        $data = mysqli_query($dbc, $query);
        if (mysqli_num_rows($data) == 0){
          // The username is unique, so insert the data into the database
          $query = "INSERT INTO users (id,username, password, join_date) VALUES ('$id','$username', SHA('$password1'), NOW())";
          mysqli_query($dbc, $query);

          // Confirm success with the user
          echo '<div class="alert alert-info">New account has been successfully created.</div>';

          mysqli_close($dbc);
          exit();
        }
        else {
          // An account already exists for this username, so display an error message
          echo '<div class="alert alert-danger">You have entered wrong registration no.</div>';
          $username = "";
        }
      }
      else{
        echo'<div class="alert alert-danger">Your Password is too weak (minimum 8 charachters)</div>';
      }
    }
    else {
      echo '<div class="alert alert-danger">You must enter all of the sign-up data, including the desired password twice.</div>';
    }
  }

  mysqli_close($dbc);
?>

  <p>Please enter your username and desired password to sign up to Central-library</p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" width=500px>
      <div class="form-group">
        <div class="form-group">
          <label for="id">Registration No.:</label>
          <input type="text" class="form-control" name="id">
        </div>
        <label for="username">Username:</label>
        <input type="text" class="form-control" name="username">
      </div>
      <div class="form-group">
        <label for="password1">Password:</label>
        <input type="password" class="form-control" name="password1">
      </div>
      <div class="form-group">
        <label for="password2">Password:</label>
        <input type="password" class="form-control" name="password2">
      </div>
      <button type="submit" class="btn btn-default" name="submit">Sign in</button>
    </form>
  </form>
</body>
</html>
