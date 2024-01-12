<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
  //session_start();
  require_once ("database.php");
  
  
  
  
	  //Email address of the receiver
		$to = "[Buyer email ad]";
		//Email subject
		$subject = 'Update on Auction for [Item Title and Item ID]';
		//Set the email body
		$message ='
		<p style="font-size:16px;"> Update on the ongoing auction for [Item Title]</p> 
		<ol>
		<li> New highest bid [Current Higest Bid]</li>
		<li> [text] </li>
		<li>[text] </li>
		</ol>
		<p>Thank you for your order.</p>
		';
		//New line characters
		$nl = "\r\n";
		//Header information
		$headers = 'MIME-Version: 1.0'.$nl;
		$headers .= 'Content-type: text/html; charset=iso-8859-1'.$nl;
		$headers .= 'To:  Buyer full name <buyer email>'.$nl;
		$headers .= 'From: Admin <[admin email]>'.$nl;
		//Send email
		if(mail($to, $subject, $message, $headers))
  		 echo "Email sent successfully.";
		else
   			echo "Unable to send the email.";
   
  	
?>
