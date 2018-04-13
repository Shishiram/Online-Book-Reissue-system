<?php
session_start();

// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['id'])) {
  if (isset($_COOKIE['id']) && isset($_COOKIE['username'])) {
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['username'] = $_COOKIE['username'];
  }
}
$user_id=$_GET['usersearch'];
if(!empty($user_id)){
  $dbc=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
  $user_id=mysqli_real_escape_string($dbc,trim($_GET['id']));
   if(!empty($user_id)){
     $query = "SELECT book_name, call_no, acc_no, date_posted FROM books WHERE id = '$user_id' ";
     $data=mysqli_query($dbc,$query) or die(mysqli_error($dbc));
     if(mysqli_num_rows($data)>=1){
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
       while ($row = mysqli_fetch_array($data)){
         echo '<tr>';
         echo '<td>' .$row['book_name'] . '</td>';
         echo '<td>' .$row['acc_no']. '</td>';
         echo '<td>' .$row['call_no'] .'</td>';
         echo '<td>' .$row['date_posted'].'</td>';
         echo '<td><button type"submit" class="btn btn_default" name="submit"> permit</button>';
         echo '</tr>';
       }
       echo'</table>';
     }
     else{
       echo 'Wrong Registration No.';
     }
   }
   else{
     echo 'Sorry, you must enter Registration No. to search.';
   }
 }
