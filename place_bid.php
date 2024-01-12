<?php 
include_once("header.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require ('/phpmailer/phpmailer/src/Exception.php');
require ('/phpmailer/phpmailer/src/SMTP.php');
require_once "/phpmailer/phpmailer/src/PHPMailer.php";
?>

<div class="container my-5">

<?php

// Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

// NOTE: listing.php calls this file and sends bid variable via POST

// session_start()
require_once "database.php";

// because bid is form input data, we need to clean it up

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $bid = $_POST["bid"];  // this is a numeric value - the bid amount given by a buyer
  $item_id = $_POST["itemid"];
  $buyer_ID = $_SESSION["id"];
  $buyer_Email = $_SESSION["email"];

// cleans data to prevent hacking
  $bid = trim($bid);
  $bid = stripslashes($bid);
  $bid = htmlspecialchars($bid);

  // test input for numeric type and if higher than 0
  if ($bid > 0 && (is_numeric($bid) == 1)) {
    $sql = "SELECT currentOffer FROM auction WHERE itemID=$item_id";
    $queryResult = $mysqli->query($sql);
    $result = $queryResult -> fetch_row()[0];

    $sql2 = "SELECT numOfBids FROM auction WHERE itemID=$item_id";
    $queryResult2 = $mysqli->query($sql2);
    $result2 = $queryResult2 -> fetch_row()[0];
    $newNumOfBids = $result2 + 1;

    if ($result < $bid) {
      $offerSql = "UPDATE auction SET currentOffer = $bid WHERE itemID=$item_id";
      $offerResult = $mysqli->query($offerSql);

      $buyerSql = "UPDATE auction SET buyerID = $buyer_ID WHERE itemID=$item_id";
      $buyerResult = $mysqli->query($buyerSql);

      $buyerSqlItem = "UPDATE item SET buyerID = $buyer_ID WHERE itemID=$item_id";
      $buyerItemResult = $mysqli->query($buyerSqlItem);

      $bidNumSql = "UPDATE auction SET numOfBids = $newNumOfBids WHERE itemID=$item_id";
      $bidNumResult = $mysqli->query($bidNumSql);

      echo('<div class="text-center">Bid successfully placed! <a href="listing.php?item_id='.$item_id[0].'">Return to listing.</a></div>');
      $sql_itemName = "SELECT itemName
      FROM item
      where itemID = $item_id";
      $itemNameQuery = $mysqli->query($sql_itemName);
      $item_Name = $itemNameQuery -> fetch_row()[0];      
      
            $mail = new PHPMailer();
            $mail->isSMTP();
            //$mail -> SMTPDebug = true;
            $mail->SMTPAuth = true;
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '8b8322d0258801'; //Use your UCL email address
            $mail->Password = '7a97d3a064fb7d'; // UCL email password
            $mail->setFrom('ucabtuw@ucl.ac.uk', '8b8322d0258801'); // UCL email address and Username
            $mail->addAddress($_SESSION["email"], $_SESSION["userName"]); //Reciever email address and name
            
            
            $mail->Subject = 'Update on Auction for '.$item_Name.' #'.$item_id;

            $mail->isHTML(true);

            $mailContent = '<p style="font-size:16px;"> Update on the ongoing auction for '.$item_Name.'</p> 
            <p><span>Congratulations!!</span></p>
            <p>You are now the highest bidder with a Price with : £'.number_format($bid, 2).'</p>
            <p>Thank you for your order.</p>';
            $mail->Body = $mailContent;

            if($mail->send()){
              echo '<div class="text-center">Email sent successfully!</div>';
              }else{
                  echo 'Email could not be sent.';
                  echo 'Mailer Error: ' . $mail->ErrorInfo;
              }
    }
    else {
      echo('<div class="text-center">Error: Your bid is lower than the current highest bid. <a href="listing.php?item_id='.$item_id[0].'">Return to listing.</a></div>');
    }
  }
}



// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   $bid = clean_data($_POST["bid"]);  // this is a numeric value - the bid amount given by a buyer
//   return $bid
// }

// // calls clean_data function to prevent hacking

// function clean_data($data) {
//   $data = trim($data);
//   $data = stripslashes($data);
//   $data = htmlspecialchars($data);
//   return $data
// }

// // sql queries to add bid to auction database

// if (test_data($bid) == 1) {

//   global $item_id; // global declared in listings.php
//   $sql = "SELECT currentOffer FROM auction WHERE auctionID=$item_id";
//   $result = mysqli_query($mysqli, $sql);

//   if ($result < $bid) {
//     $sql = "INSERT INTO auction (currentOffer)
//     VALUES $bid"
//     $query = mysqli_query($mysqli, $sql);

//     $sql2 = "INSERT INTO auction (buyerID)
//     VALUES $_SESSION['buyerID']"
//     $query2 = mysqli_query($mysqli, $sql);

//     echo 'Bid successfully placed!'
//     header('Location: browse.php');
//    }

//   else {
//      echo 'Error: Your bid is lower than the current highest bid.'
//      header('Location: browse.php');
//    }
// }

// //$connection->close();

// // test input for numeric type and if higher than 0

// function test_data($data) {
//   if ($data > 0 && (is_numeric($data) == 1))
//   return 1
// }

?>

</div>

<?php include_once("footer.php")?>
