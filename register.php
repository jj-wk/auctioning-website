<?php include_once("header.php")?>

<div class="container">
<h2 class="my-3">Register new account</h2>
<?php 
        if(isset($_SESSION["errorReg"])){
            echo '<div class="alert alert-danger">' . $_SESSION["errorReg"] . '</div>';
            //echo $_SESSION["error"]; Trial and error
            unset($_SESSION['errorReg']);//if user refresh index.php after 1st time it will not see the message
        }        
        ?>
<!-- Create auction form --->
<form method="POST" action="process_registration.php" id="myForm">

  <div class="form-group row">
    <label for="role" class="col-sm-2 col-form-label text-right">Registering as a:</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="role" id="accountBuyer" value="buyer" checked>
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="role" id="accountSeller" value="seller">
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <small id="roleHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>

  <div class="form-group row">
    <label for="firstName" class="col-sm-2 col-form-label text-right">First Name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="firstName" placeholder="First Name" name ="firstName" required>
      <small id="firstNameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>

  <div class="form-group row">
    <label for="lastName" class="col-sm-2 col-form-label text-right">Last Name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="lastName" placeholder="Last Name" name= "lastName" required>
      <small id="lastNameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>

  <div class="form-group row">
    <label for="userName" class="col-sm-2 col-form-label text-right">User Name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="userName" placeholder="User Name" name="userName" required>
      <small id="userNameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  
  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="email" placeholder="Email" name ="email" required>
      <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>

  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required onkeyup='check();'>
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
  <div class="form-group row">
    <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="passwordConfirmation" name ="passwordConfirmation" placeholder="Enter password again" required onkeyup='check();'>
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      <span id='message'></span>
    </div>
  </div>
  <div class="form-group row">
    <button type="submit" class="btn btn-primary form-control">Register</button>
  </div>
</form>

<div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>

</div>


<?php include_once("footer.php")?>