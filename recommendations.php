<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<?php
  // This page is for showing a buyer recommended items based on their bid
  // history. It will be pretty similar to browse.php, except there is no
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.

  //session_start();
  require_once ("database.php");

  // Check user's credentials (cookie/session).
 
  if (!isset($_SESSION['role']) || $_SESSION['role'] != 'buyer') {
    echo "You don't have access to this page.";
    header('Location: browse.php');
  }

  // Perform a query to pull up auctions they might be interested in.



 else {
     $sql = "SELECT * FROM item WHERE buyerID = '".$_SESSION['id']."'"; // access categories matching those of items buyer owns

     if ($result = $mysqli -> query($sql)) {
      while ($category = $result -> fetch_row()) {
        $sql2 = "SELECT * FROM auction  WHERE itemID=$category[0]";
        if ($result2 = $mysqli -> query($sql2)) {
          while ($auctionInfo = $result2 -> fetch_row()) {
            echo "<br> Because you bid on items in the categories: " .$category[5]. "<br> You might be interested in: <i>" .$category[3]. "</i><br> Price: £<i>" .$auctionInfo[7]. "</i>";
          }

        //echo "<br> Because you bought items in the categories: " .$category[4]. "<br>";
      }
      
    }
  }
}

      // access items in auction that match category(s) user has bought in previously

    // $sql2 = "SELECT item.itemName, auction.auctionID
    // FROM auction
    // INNER JOIN item ON item.buyerID=auction.buyerID
    // WHERE category= $category";

    // $query = mysqli_query($mysqli, $sql2);

    // echo $query;


    //   // print out recommendations

    // if (mysqli_num_rows($query) > 0) {
    //    while($row = $query->fetch_assoc()) {
    //      echo "<br> You might be interested in: ". $row["itemName"] . "<br>";
    //     }
    //   }
    // }
      

?>
