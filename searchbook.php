<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Books -Search</title>
</head>
<body>
  <h3>Books -search results</h3>

  <?php
  // This function builds a search query from the search keywords and sort setting
  function build_query($user_search, $sort){
    $search_query = "SELECT * FROM booksdb";

    //Extract  the search keywords into an Array
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);
    $final_search_words = array();
    if (count($search_words) > 0){
      foreach($search_words as $word){
        if(!empty($word)){
          $final_serch_words[] = $word;
        }
      }
    }
    $where_list = array();
    if(count($final_search_words) > 0){
      foreach ($final_search_words as $word) {
      $where_list[] = "description LIKE '%$word%'";
    }
  }
  $where_clause = implode('OR' , $where_list);
  if(!empty($where_clause)){
    $search_query .= "WHERE $where_clause";
  }
  //sort the search query using the sort setting
  switch($sort){
    //Ascending by job title
    case 1:
    $search_query .= "ORDER BY book_name";
    break;
    //Descending by job title
    case 2:
    $search_query .= "ORDER BY book_name DESC";
    break;
    // Ascending by state
    case 3:
      $search_query .= " ORDER BY author";
      break;
    // Descending by state
    case 4:
      $search_query .= " ORDER BY author DESC";
      break;
    // Ascending by date posted (oldest first)
    case 5:
      $search_query .= " ORDER BY count";
      break;
    // Descending by date posted (newest first)
    case 6:
      $search_query .= " ORDER BY count DESC";
      break;
    default:
      // No sort setting provided, so don't sort the query
  }
  return $search_query;
}
//This function builds heading based on the specified sort setting
  function generate_sort_links($user_search, $sort){
    $sort_links='';

    switch($sort){
      case 1:
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Book name</a><td>Description</td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Author</a></td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Count</a></td>';
        break;
      case 3:
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Book name</a></td><td>Description</td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">Author</a></td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Count</a></td>';
        break;
      case 5:
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Book name</a></td><td>Description</td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Author</a></td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Count</a></td>';
        break;
      default:
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Book name</a></td><td>Description</td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Author</a></td>';
        $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Count</a></td>';
      }

      return $sort_links;
    }
//this function returns page likns
function generate_page_links($user_search, $sort, $cur_page, $num_pages){
  $page_links = '';

  if($cur_page > 1){
    $page_links .= '<a href="'. $_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&sort='.$sort.'&page=' . ($cur_page - 1) . '" > << </a>';
  }
  else{
    $page_links .= ' <<';
  }
  //loop through the pages generating the number links
  for($i = 1; $i<=$num_pages; $i++){
    if($cur_page == $i){
      $page_links .= ' ' . $i;
    }
    else{
    $page_links .= '<a href="' . $_SERVER['PHP_SELF'] .'?usersearch=' .$user_search. '&sort='.$sort.'&page=' .$i. '">'.$i.'</a>';
    }
  }

  if ($cur_page < $num_pages) {
    $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">-></a>';
  }
  else {
    $page_links .= ' ->';
  }
  return $page_links;
}

    $sort = $_GET['sort'];
    $user_search=$_GET['usersearch'];

    $cur_page = isset($_GET['page'])? $_GET['page'] : 1;
    $results_per_page = 5; // number of results per page
    $skip = (($cur_page-1)* $results_per_page);


    echo '<tr class="heading">';
    echo generate_sort_links($user_search, $sort);
    echo '</tr>';

    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


    $query = build_query($user_search, $sort);
    $result = mysqli_query($dbc, $query);
    $total = mysqli_num_rows($result);
    if($total > 0){
    $num_pages = ceil($total/ $results_per_page);

    $query=$query . "LIMIT $skip, $results_per_page";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
      echo '<tr class="results">';
      echo '<td valign="top" width="20%">' .$row['book_name'] . '</td>';
      echo '<td valign="top" width="10%">' .$row['author'] . '</td>';
      echo '<td valign="top" width="10%">' .$row['count'] . '</td>';
      echo '</tr>';
    }

    if($num_pages>0){
      echo generate_page_links($user_search, $sort, $cur_page, $num_pages);
    }
  }
  else{
    echo '<p> No Results were found</p>';
  }
    mysqli_close($dbc);

   ?>
 </body>
 </html>
