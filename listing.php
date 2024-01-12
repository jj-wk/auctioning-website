<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
  // Get info from the URL:
  $item_id = $_GET['item_id'];

  // Use item_id to make a query to the database.
  //session_start();
  require_once "database.php";

  // else {
    $sql_itemname = "SELECT item.itemName, auction.auctionID
    FROM auction
    INNER JOIN item ON item.itemID=auction.itemID
    WHERE item.itemID=$item_id";

    $sql_currentoffer = "SELECT currentOffer
    FROM auction
    where itemID=$item_id";

    $sql_endtime = "SELECT endDate
    FROM auction
    where itemID=$item_id";

    $sql_description = "SELECT itemDetails
    FROM item
    where itemID=$item_id";


    $titleQuery = $mysqli->query($sql_itemname);
    $title = $titleQuery -> fetch_row()[0];

    $current_priceQuery = $mysqli->query($sql_currentoffer);
    $current_price = $current_priceQuery -> fetch_row()[0];

    $end_timeQuery = $mysqli->query($sql_endtime);
    $end_time = $end_timeQuery -> fetch_row()[0];
    $end_time = new DateTime($end_time);

    $descriptionQuery = $mysqli->query($sql_description);
    $description = $descriptionQuery -> fetch_row()[0];

  // }

  // Calculate time to auction end:
  $now = new DateTime();

  if ($now < $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
  }

  // TODO: If the user has a session, use it to make a query to the database
  //       to determine if the user is already watching this item.
  //       For now, this is hardcoded.
  $buyer_ID = $_SESSION["id"];
  
  if (isset($_SESSION["logged_in"])) {
    $has_session = true;
    $watching = false;

    $sql_wishList = "SELECT wishList
    FROM buyer
    where buyerID=$buyer_ID";
    $wishListQuery = $mysqli->query($sql_wishList);
    $wishList = $wishListQuery -> fetch_row()[0];
    $wishList = explode(",", $wishList);

    if (in_array($item_id, $wishList)) {
      $watching = true;
    }
    else {
      $watching = false;
    }
  }
  else {
    $has_session = false;
    $watching = false;
  }
?>


<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($title); ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if ($now < $end_time && $has_session && $_SESSION["role"] == 'buyer'):
?>
    <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
    </div>
<?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->

    <div class="itemDescription">
    <?php echo($description); ?>
    </div>

  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <p>
<?php if ($now > $end_time): ?>
     This auction ended on <i><?php echo(date_format($end_time, 'j M H:i')) ?></i>,
     and it sold for a final price of <i>£<?php echo($current_price) ?></i>.
<?php else: ?>
     Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>
    <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

    <!-- Bidding form -->
    <form method="POST" action="place_bid.php">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">£</span>
        </div>
	    <input type="number" class="form-control" id="bid" name="bid">
      <input type="hidden" name="itemid" value="<?php echo($item_id) ?>" />
      </div>
      <button type="submit" class="btn btn-primary form-control">Place bid</button>
    </form>
<?php endif ?>


  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->



<?php include_once("footer.php")?>


<script>
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    success:
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
        console.log(objT);

        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success:
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
        console.log(objT);

        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>
