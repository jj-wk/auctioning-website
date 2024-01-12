<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

// Initialize the session
session_start();

require_once "database.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if( empty($_POST["email"]) || 
        empty($_POST["password"]) ||
        empty($_POST["email"]) || 
        empty($_POST["password"]) ||
        empty($_POST["email"]) || 
        empty($_POST["password"]) ||
        empty($_POST["passwordConfirmation"])
        ){
        $_SESSION["errorReg"] = "Please fill out all empty fields. ";
        header("refresh:0;url=register.php"); }
        
    else {

        if( $_POST['password'] !== $_POST['passwordConfirmation']){
        $_SESSION["errorReg"] = "Password and Confirm password should maatch! ";
        echo $_SESSION["errorReg"];
        header("refresh:1;url=register.php");
        }

        else{
        //set these variable
        $role = trim($_POST["role"]);
        $firstName = trim($_POST["firstName"]);
        $lastName = trim($_POST["lastName"]);    
        $userName = trim($_POST["userName"]);    
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        

        //check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/",$userName)) {
            $nameErr = "Only letters and white space allowed";
            echo $nameErr;
            }

            else {
            //For encrypted password
            //$sql = "INSERT INTO `user` (`userName`,`password`,`email`,`role`,`firstName`,`lastName`) VALUES ('".$userName."','".md5($password)."','".$email."','".$role."','".$firstName."','".$lastName."' );";
            $sql = "INSERT INTO `user` (`userName`,`password`,`email`,`role`,`firstName`,`lastName`) VALUES ('".$userName."','".$password."','".$email."','".$role."','".$firstName."','".$lastName."' );";

            $result = $mysqli->query($sql);

            if ($role == 'seller') {
                $sql2 = "INSERT INTO `seller` (`username`, `sellerID`) VALUES ('$userName', (SELECT `id` FROM `user` ORDER BY `id` DESC LIMIT 1));";
            }
            else {
                $sql2 = "INSERT INTO `buyer` (`username`, `buyerID`) VALUES ('$userName', (SELECT `id` FROM `user` ORDER BY `id` DESC LIMIT 1));";
            }

            $result2 = $mysqli->query($sql2);

            $mysqli->close();
            echo "Successful";
            header("refresh:0.5;url=index.php"); 
            }
        }
    } 
}

?>