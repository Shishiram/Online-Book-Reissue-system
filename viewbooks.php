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
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Central-library :View books</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <h3>Central-library View books</h3>

<?php
  require_once('appvars.php');
  require_once('connect.php');
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Grab the profile data from the database
  if (!isset($_GET['id'])){
    $query = "SELECT book_name, call_no, acc_no, date_posted FROM books WHERE id = '" . $_SESSION['id'] . "'";
  }
  else {
    $query = "SELECT book_name, call_no, acc_no, date_posted FROM books WHERE id = '" . $_GET['id'] . "'";
  }
  $data = mysqli_query($dbc, $query);
  if (isset($_SESSION['username'])) {
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
  if (mysqli_num_rows($data) >0){
    echo'<table class="table table-bordered">';
    echo'<thead>
      <tr>
        <th>Bookname</th>
        <th>Account No.</th>
        <th>Call No.</th>
        <th>Date for reissue.</th>
        <th>Reissue </th>
      </tr>
    </thead>';
    $total=0;
    while ($row = mysqli_fetch_array($data)){
      echo '<tr>';
      echo '<td>' .$row['book_name'] . '</td>';
      echo '<td>' .$row['acc_no']. '</td>';
      echo '<td>' .$row['call_no'] .'</td>';
      echo '<td>' .$row['date_posted'].'</td>';
      $date1="now";
      $date2= $row['date_posted'];
      //if($days_between<)
      //echo 'now:'.$date1.'</br>';
      //echo 'date1:'.strtotime($date1).'</br>';
      //echo 'reissue:'.$date2.'</br>';
      //echo 'date2:'.strtotime($date2).'</br>';
      if(strtotime($date1) <= strtotime($date2)){
        $start = strtotime($date1);
        $end = strtotime($date2);
        $days_between = ceil(abs($start - $end) / 86400);
        $days_between-=1;
        echo $days_between.'</br>';
        if($days_between<15){
          echo'<form method="post" action="update.php">
          <input type="hidden" name="book" value="'.$row['book_name'].'">
          <td><button type="submit" class="btn btn_default" name="submit">Reissue</button></td>
          </form>';
        }
        else{
          echo'<td>Can not reissue before 15 days</td>';
        }
      }
      else {
        $start = strtotime($date1);
        $end = strtotime($date2);
        $days_between = ceil(abs($end - $start) / 86400);
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
        $total+=$fine;
        echo '<td><button type="submit" class="btn btn_default" name="submit"><a href="fine.php">PayFine Rs.'.$fine.'</a></button>';
      }
      echo '</tr>';
    }
    echo'</table>';
    echo '<div class="alert alert-info">Pay Total fine Rs.'.$total.'</div>';
  } // End of check for a single row of user results
  else {
    echo '<div class="alert alert-info">No books issued.</div>';
  }
  mysqli_close($dbc);
?>
</body>
</html>
