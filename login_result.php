<?php 
include_once("header.php")
?>

<div class="container my-5">

<?php

// Initialize the session
//session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){
    //header("location: welcome.php");
    header("refresh:5;url=index.php");
    exit;
}

// Include config/database file
//require_once "config.php";
require_once "database.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
//$login_err = "Waow";
 

// Processing form data when form is submitted

//Checks if the fields are empty
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["email"]) || empty($_POST["password"])){
        $_SESSION["error"] = "Invalid email or password. ";
        header("refresh:0;url=index.php");
    }
//set these variable    
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
   
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, userName, password, role, email FROM user WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_userName);
            
            // Set parameters
            $param_userName = $email;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                $count = $stmt->num_rows;
                //echo $count;
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){ 
                                       
                    // Bind result variables
                    $stmt->bind_result($id, $userName, $hashed_password, $role, $email);
                    
                    if($stmt->fetch()){
                        
                        //use this during register $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        //if(password_verify($password, $hashed_password)){

                        if($password === $hashed_password){
                            // Password is correct, so start a new session
                            //session_start(); I'm not sure why I removed this
                            
                            // Store data in session variables
                            $_SESSION["logged_in"] = true;
                            isset($_SESSION["logged_in"])== true;
                            $_SESSION["id"] = $id;
                            $_SESSION["userName"] = $userName;                            
                            $_SESSION["role"] = $role;
                            $_SESSION["email"] = $email;
                            echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
                            // Redirect user to welcome page
                            header("refresh:5;url=index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $_SESSION["error"] = "Invalid username or password. ";
                            header("refresh:0.5;url=index.php");
                        }
                    }
                    else{
                        // Username doesn't exist, display a generic error message
                        $_SESSION["error"] = "Invalid username or password. ";
                        header("refresh:0.5;url=index.php");
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $_SESSION["error"] = "Invalid username or password. ";
                    header("refresh:0.5;url=index.php");
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
        else{
        $_SESSION["error"] = "Invalid username or password. ";
        header("refresh:0.5;url=index.php"); 
        }
}
/* Close connection */
$mysqli -> close();
}
?>

</div>


<?php include_once("footer.php")?>