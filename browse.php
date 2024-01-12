<?php include 'database.php'; ?>
<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          
          <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder="Search for anything">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat">
          <option selected value="all">All categories</option>
          <?php 
              $sql0 = "SELECT DISTINCT category FROM item";
              if ($result0 = $mysqli -> query($sql0)){
                while ($optionsCat = $result0 -> fetch_array()) {
                  echo ('<option value='.$optionsCat[0].'>'.$optionsCat[0].'</option>');
                }
             }
          ?>
        </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <select class="form-control" id="order_by" name="order_by">
          <option selected value="pricelow2high">Price (low to high)</option>
          <option value="pricehigh2low">Price (high to low)</option>
          <option value="date">Soonest expiry</option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->


</div>

<?php
  // Retrieve these from the URL
  if (!isset($_GET['keyword'])) {
    // TODO: Define behavior if a keyword has not been specified.
    //$error = "Missing Keyword";
    //echo "Error: keyword is undefined.";
    //header('url: browse.php');				// error here - remove redirect
    $keyword = "";
  }
  else {
    $keyword = $_GET['keyword'];
  }

  if (!isset($_GET['cat'])) {
    // TODO: Define behavior if a category has not been specified.
    //$error = "Missing Category";
    $category = "";
  }
  else {
    $category = $_GET['cat'];
  }
  
  if (!isset($_GET['order_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
    //$error = "Missing Order";
    $ordering = "";
  }
  else {
    $ordering = $_GET['order_by'];
  }
  
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }

  /* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
     if($keyword == NULL){
      //echo "NULL";
      $keywordSearch = "itemName IS NOT NULL";
     }
     else{
      $keywordSearch = "itemName LIKE '%$keyword%'";
     }

     if($category == "all"){
      //echo "NULL";
      $categorySearch = "category IS NOT NULL";
     }
     else{
      $categorySearch = "category LIKE '%$category%'";
     }

     if($ordering == "pricelow2high"){
      //echo "NULL";
      $Sort = "ORDER BY (SELECT auction.currentOffer FROM auction WHERE auction.itemID = item.itemID) ASC";
     }
     else if ( $ordering == "pricehigh2low"){
      $Sort = "ORDER BY (SELECT auction.currentOffer FROM auction WHERE auction.itemID = item.itemID) DESC";
     }
     else if ( $ordering == "date"){
      $Sort = "ORDER BY (SELECT auction.endDate FROM auction WHERE auction.itemID = item.itemID) ASC";
     }
     else {
      $Sort = "ORDER BY itemID DESC";
     }



     $sql5 = "SELECT * FROM item WHERE $keywordSearch AND $categorySearch $Sort";      
     if ($result5 = $mysqli -> query($sql5)){
      $count5 = $result5->num_rows;
      while ($newItems = $result5 -> fetch_array()) {
        $sql9 = "SELECT * FROM auction WHERE auction.itemID = $newItems[0]" ;
        if ($result9 = $mysqli -> query($sql9)){
          $count9 = $result9->num_rows;
          while ($newItemsP = $result9 -> fetch_array()) {
            //$item_Price[] = $newItemsP['startPrice'];
          }
        }
       }
     };
  


  /* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
  
  $sql7 = "SELECT COUNT(itemID) FROM item WHERE quantity IS NOT NULL";
  $result7 = $mysqli -> query($sql7);
  $counts = $result7 -> fetch_row();
  $count7 = $counts[0];

  // TODO: Calculate me for real
  if ($count7 == $count5){
    $num_results = $count7;
  }
  else{
    $num_results = $count5;
  }
   
  $results_per_page = 3;
  $max_page = ceil($num_results / $results_per_page);
?>

<div class="container mt-5">

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->


<ul class="list-group">

<!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->

<?php

  // Demonstration of what listings will look like using data from the DB.
  $limit = 'LIMIT ' .($curr_page - 1) * $results_per_page .',' .$results_per_page;
     


  if ($count7 == $count5 && $count5 !== 0){
    $sql = "SELECT * FROM item WHERE quantity is not null $Sort $limit";
    if ($result = $mysqli -> query($sql)){
      //echo $priceSort;
      while ($items = $result -> fetch_array()) {
        $eachItemID = $items['itemID'];
        $sql1 = "SELECT * FROM auction WHERE itemID = $eachItemID";
        if ($result1 = $mysqli -> query($sql1)){
          while ($auctions = $result1 -> fetch_array()) {
            $item_id = $items['itemID'] ;
            $title = $items['itemName'];
            $description = $items['itemDetails'];
            $current_price = $auctions['currentOffer'];
            $end_time = new DateTime($auctions['endDate']);
            //$itemCategory[] = $items['category'];
            $num_bids = $auctions['numOfBids'];
            print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_time);
          }
        }
      }
    }
   }



  
  else if ($count7 !== $count5 && $count5 !== 0) {
    $sql5 = "SELECT * FROM item WHERE $keywordSearch AND $categorySearch $Sort $limit";
    if ($result5 = $mysqli -> query($sql5)){
      $count5 = $result5->num_rows;
      while ($newItems = $result5 -> fetch_array()) {
        $sql9 = "SELECT * FROM auction WHERE auction.itemID = $newItems[0]" ;
        if ($result9 = $mysqli -> query($sql9)){
          $count9 = $result9->num_rows;
          //echo $count9;
          while ($newItemsP = $result9 -> fetch_array()) {
            $item_id = $newItems['itemID'] ;
            $title = $newItems['itemName'];
            $description = $newItems['itemDetails'];
            $current_price = $newItemsP['currentOffer'];
            $end_time = new DateTime($newItemsP['endDate']);
            $num_bids = $newItemsP['numOfBids'];
            print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_time);
          }
        }
        
       }
       
     }
     
  }

  else {
    echo('
    <li class="list-group-item d-flex justify-content-between">
    <div class="p-2 mr-5"><h5>There are currently no items for auction.</h5></div>
    <div class="text-center text-nowrap">Check back later..</div>
  </li>'
  );
  }

?>

</ul>

<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>

<?php include_once("footer.php")?>
