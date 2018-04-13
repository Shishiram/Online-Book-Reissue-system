<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <title>Central-library Admin-Signup</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Central-Library Admin-Signup</h3>
  <table class="table table-bordered table-condensed">
      <tbody>
          <tr>
             <td><input type="text" class="form-control" /></td>
             <td><input type="text" class="form-control" /></td>
             <td><input type="text" class="form-control" /></td>
             <td><input type="text" class="form-control" /></td>
             <td><input type="text" class="form-control" /></td>
             <td><input type="text" class="form-control" /></td>
          </tr>
      </tbody>
  </table>
</body>
</html>
<?php
require_once('appvars.php');
require_once('connect.php');
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
  if (isset($_SESSION['username'])) {
    echo '<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <img class="navbar-brand" src="nitwlogo1.png" alt="logo photo" width="50" height="50">
        <a class="navbar-brand" href="#">Central-Library</a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="adminsearch.php">Search Student</a></li>
        <li><a href="issue.php">Issue books</a></li>
        <li><a href="signup.php">Student Sign-Up</a></li>
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
        <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <li><a href="signup1.php"><span class="glyphicon glyphicon-user"></span> Admin-Sign Up</a></li>
        <li><a href="login1.php"><span class="glyphicon glyphicon-log-in"></span> Admin-Login</a></li>
    </ul>
    </div>
    </nav>';
  }
  if(isset($_POST['submit'])){
    $dbc=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $user_id=mysqli_real_escape_string($dbc,trim($_POST['id']));
     if(!empty($user_id)){
       $query = "SELECT book_name, call_no, acc_no, date_posted FROM books WHERE id = '$user_id' ";
       $data=mysqli_query($dbc,$query) or die(mysqli_error($dbc));
       if(mysqli_num_rows($data)>=1){
         echo'<table class="table table-bordered">';
         echo'<thead>
           <tr>
             <th>Registrtion No.</th>
             <th>Bookname</th>
             <th>Account No.</th>
             <th>Call No.</th>
             <th>Date for reissue.</th>
             <th>Fine</th>
             <th>Reissue </th>
           </tr>
         </thead>';
         while ($row = mysqli_fetch_array($data)){
           $date1="now";
           $date2= $row['date_posted'];
           if(strtotime($date1) >= strtotime($date2)){
             $start = strtotime($date1);
             $end = strtotime($date2);
             $days_between = ceil(abs($start - $end) / 86400);
             //echo $days_between.'</br>';
             $fine=0;
             $days_between--;
             if($days_between<=7){
               $fine+=$days_between;
             }
             else if($days_between>7 && $days_between<=30){
               $fine+=7;
               $fine+=(2*($days_between-7));
             }
             else{
               $fine+=7;
               $fine+=(2*23);
               $fine+=(3*($days_between-30));
             }
             echo '<tr>';
             echo '<td>' .$user_id . '</td>';
             echo '<td>' .$row['book_name'] . '</td>';
             echo '<td>' .$row['acc_no']. '</td>';
             echo '<td>' .$row['call_no'] .'</td>';
             echo '<td>' .$row['date_posted'].'</td>';
             echo '<td>' .$fine.'</td>';
             echo'<form method="post" action="permit.php">
              <input type="hidden" name="book" value="'.$row['book_name'].'">
              <input type="hidden" name="id" value="'.$user_id.'">
              <td><button type="submit" class="btn btn_default" name="submit">Permit</button>
              </form>';
             echo '</tr>';
           }
         }
         echo'</table>';
       }
       else{
         echo '<div class="alert alert-danger"> Wrong Registration No.</div>';
       }
     }
     else{
       echo '<div class="alert alert-danger">Sorry, you must enter Registration No. to search.</div>';
     }
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" width="500px">
  <div class="form-group">
    <label for="id">registration no:</label>
    <input type="text" class="form-control" name="id">
  <button type"submit" class="btn btn_default" name="submit"> Submit</button>
  </div>
</form>
</body>
</html>
