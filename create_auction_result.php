<?php 
include_once("header.php")
?>

<div class="container my-5">

<?php
require_once "database.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $auctionTitle = $_POST["auctionTitle"];
    $auctionDetails = $_POST["auctionDetails"];
    $auctionCategory = $_POST["auctionCategory"];
    $auctionQuantity = trim($_POST["auctionQuantity"]);
    $auctionStartPrice = trim($_POST["auctionStartPrice"]);
    $auctionEndDate = trim($_POST["auctionEndDate"]);
    $auctionStartDate = date("Y-m-d H:i:s");
    $auctionNumOfBids = 0;

    if (empty($auctionTitle) or $auctionCategory == "Choose..." or empty($auctionStartPrice) or empty($auctionEndDate)) {
        header("refresh:0;url=create_auction.php");
    }
    else if (empty($auctionQuantity)) {
        $auctionQuantity = 1;
    }

    $seller_ID = $_SESSION["id"];

    $sql = "INSERT INTO `item` (`itemName`,`itemDetails`,`category`,`quantity`,`sellerID`) 
    VALUES ('".$auctionTitle."', '".$auctionDetails."', '".$auctionCategory."', ".$auctionQuantity.", ".$seller_ID.");";

    $result = $mysqli->query($sql);

    $sql3 = "INSERT INTO `auction` (`itemID`,`startPrice`,`endDate`, `startDate`, `sellerID`, `currentOffer`, `numOfBids`) 
    VALUES ((SELECT `itemID` FROM `item` ORDER BY `itemID` DESC LIMIT 1),'".$auctionStartPrice."', '".$auctionEndDate."', '".$auctionStartDate."', ".$seller_ID.", '".$auctionStartPrice."', '".$auctionNumOfBids."');";

    $result = $mysqli->query($sql3);
}

$sqlItemID = "SELECT `itemID` FROM `item` ORDER BY `itemID` DESC LIMIT 1";
$sqlItemIDQuery = $mysqli->query($sqlItemID);
$item_id = $sqlItemIDQuery -> fetch_row();


// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="listing.php?item_id='.$item_id[0].'">View your new listing.</a></div>');

?>

</div>


<?php include_once("footer.php")?>