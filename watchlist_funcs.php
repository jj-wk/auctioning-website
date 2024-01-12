<?php 
require_once "database.php";
?>
 
 <?php

session_start();

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

// Extract arguments from the POST variables:
$item_id = $_POST['arguments'][0];
$buyer_ID = $_SESSION["id"];

$sql_wishList = "SELECT wishList
FROM buyer
where buyerID=$buyer_ID";
$wishListQuery = $mysqli->query($sql_wishList);
$wishList = $wishListQuery -> fetch_row()[0];

if ($_POST['functionname'] == "add_to_watchlist") {
  // TODO: Update database and return success/failure.
  if ($wishList == null) {
    $updateListSql = "UPDATE buyer SET wishList = $item_id WHERE buyerID=$buyer_ID";
    $updateResult = $mysqli->query($updateListSql);
  }
  else {
    $wishList = explode(",", $wishList);
    array_push($wishList, $item_id);
    $wishList = implode(",", $wishList);

    $updateListSql = "UPDATE buyer SET wishList = '$wishList' WHERE buyerID=$buyer_ID";
    $updateResult = $mysqli->query($updateListSql);
  }

  $res = "success";
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
  // TODO: Update database and return success/failure.
  $wishList = explode(",", $wishList);
  array_splice($wishList, array_search($item_id, $wishList), 1);
  $wishList = implode(",", $wishList);
  
  $updateListSql = "UPDATE buyer SET wishList = '$wishList' WHERE buyerID=$buyer_ID";
  $updateResult = $mysqli->query($updateListSql);

  $res = "success";
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo $res;

?>