<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My bids</h2>

  <!-- This page is for showing a user the auctions they've bid on.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.


  // Check user's credentials (cookie/session). -->

<?php
  //session_start();
  require_once ("database.php");
  if (!isset($_SESSION['role']) || $_SESSION['role'] != 'buyer') {
    echo "You don't have access to this page.";
    header('Location: browse.php');
  }

  $sql = "SELECT * FROM item WHERE quantity is not null AND buyerID = ".$_SESSION["id"]." ORDER BY itemID DESC";
  $sql1 = "SELECT * FROM auction WHERE itemID =(SELECT item.itemID FROM item WHERE auction.itemID = item.itemID) AND buyerID = ".$_SESSION["id"]." ORDER BY itemID DESC"; 

  if ($result = $mysqli -> query($sql) AND $result1 = $mysqli -> query($sql1)) {
    $count = $result->num_rows;
    $count1 = $result1->num_rows;
    if ($count == 0) {
      echo '<div><p>You have not bid on any auctions.</p></div>';
    }

    while ($items = $result -> fetch_array() AND $auctions = $result1 -> fetch_array()) {
      $item_id = $items['itemID'] ;
      $title = $items['itemName'];
      $description = $items['itemDetails'];
      $current_price = $auctions['currentOffer'] ;
      $end_time = new DateTime($auctions['endDate']);
      //$itemCategory[] = $items['category'];
      $num_bids = $auctions['numOfBids'];
      print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_time);
    }
  }  
?>

<h2 class="my-3">Watching</h2>

<?php
  //session_start();
  require_once ("database.php");
  if (!isset($_SESSION['role']) || $_SESSION['role'] != 'buyer') {
    echo "You don't have access to this page.";
    header('Location: browse.php');
  }

  $sql = "SELECT * FROM item WHERE quantity is not null ORDER BY itemID DESC";
  $sql1 = "SELECT * FROM auction WHERE itemID =(SELECT item.itemID FROM item WHERE auction.itemID = item.itemID) ORDER BY itemID DESC"; 

  if ($result = $mysqli -> query($sql) AND $result1 = $mysqli -> query($sql1)) {
    $count = $result->num_rows;
    $count1 = $result1->num_rows;
    if ($count == 0) {
      echo '<div><p>You are currently not watching any items.</p></div>';
    }

    while ($items = $result -> fetch_array() AND $auctions = $result1 -> fetch_array()) {
      $sql_wishList = "SELECT wishList
      FROM buyer
      where buyerID=".$_SESSION["id"]."";
      $wishListQuery = $mysqli->query($sql_wishList);
      $wishList = $wishListQuery -> fetch_row()[0];
      $wishList = explode(",", $wishList);

      $item_id = $items['itemID'] ;
      if (in_array($item_id, $wishList)) {
        $title = $items['itemName'];
        $description = $items['itemDetails'];
        $current_price = $auctions['currentOffer'] ;
        $end_time = new DateTime($auctions['endDate']);
        //$itemCategory[] = $items['category'];
        $num_bids = $auctions['numOfBids'];
        print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_time);
      }
    }
  }  
?>

<?php include_once("footer.php")?>
