<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php include 'database.php'; ?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  if (!isset($_SESSION['role']) || $_SESSION['role'] != 'seller') {
    echo "You don't have access to this page.";
    header('Location: browse.php');
  }
  else {
    // TODO: Perform a query to pull up their auctions.
    echo "Listed below is(are) your listings: <br><br>";
    $seller_ID = $_SESSION['id'];

    ///
    $sql = "SELECT * FROM item WHERE sellerID = $seller_ID";
    $sql1 = "SELECT * FROM auction WHERE auction.sellerID = $seller_ID" ;
     if ($result = $mysqli -> query($sql) AND $result1 = $mysqli -> query($sql1)) {
      $count = $result->num_rows;
      $count1 = $result1->num_rows;
      //echo $count, $count1;
      //$items = $result -> fetch_array();
       while ($sellerItems = $result -> fetch_array() AND $sellerAuctionItems = $result1 -> fetch_array()) {
        $item_id = $sellerItems['itemID'] ;
        $title = $sellerItems['itemName'];
        $description = $sellerItems['itemDetails'];
        $current_price = $sellerAuctionItems['startPrice'];
        $end_time = new DateTime($sellerAuctionItems['endDate']);
        $num_bids = $sellerAuctionItems['numOfBids'];
        print_listing_sell_li($item_id, $title, $description, $current_price, $num_bids, $end_time);
       }
         
    }
   
    ///



  }

  
  


  
  
  
?>

<?php include_once("footer.php")?>