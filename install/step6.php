<?php
session_start();

//We check if step 5 has been completed
if(!isset($_SESSION['installation_step5'])) {
	header('location: step5.php');
	exit();
} 

include("lang/" . $_SESSION['app_language'] . ".php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = htmlspecialchars($_POST['account_username']);
	$password = htmlspecialchars($_POST['account_password']);
	$passwordConfirmation = htmlspecialchars($_POST['account_passwordConfirmation']);
	$email = htmlspecialchars($_POST['account_email']);
	
	//We check if there is any error in the inputs submitted
	$error = false;
	if(strlen($username) < 3 OR strlen($username) > 30 OR !ctype_alnum($username))
		$error = true;
	if(strlen($password) < 6 OR $password != $passwordConfirmation)
		$error = true;
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		$error = true;
	
	if(!$error) {
		$_SESSION['account_username'] = $username;
		$_SESSION['account_password'] = $password;
		$_SESSION['account_email'] = $email;
		
		$_SESSION['installation_step6'] = true;
		
		header('location: step7.php');
		exit();
	} else {
		$_SESSION['installation_error'] = $lang['invalidInputsSubmitted'];
		
		//If step 6 has been completed before and is not valid anymore with the new information provided, we block all the next steps
		if(isset($_SESSION['installation_step6']))
			unset($_SESSION['installation_step6']);
		if(isset($_SESSION['installation_step7']))
			unset($_SESSION['installation_step7']);
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $lang['administratorAccount']; ?> &bull; Setup wizard &bull; Expressif</title>
	
	<?php include("includes/header.php"); ?>
	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</head>
<body>

<?php include("includes/body.php"); ?>
   
<div class="container">
	<div class="row">
		<div class="page-header">
			<h1><small><?php echo $lang['administratorAccount']; ?></small></h1>
		</div>
		
		<?php include("errors.php"); ?>
		
		<p><?php echo $lang['step6Desc']; ?></p>
		
		<br />
		
		<form action="step6.php" method="post">
			<div class="form-group">
				<label for="account_username"><?php echo $lang['username']; ?></label>
				<input type="text" class="form-control" name="account_username" placeholder="<?php echo $lang['username']; ?>" <?php if(isset($_SESSION['account_username'])) echo 'value="' . $_SESSION['account_username'] . '"'; ?>>
				<p class="help-block"><?php echo $lang['usernameDesc']; ?></p>
			</div>
			<div class="form-group">
				<label for="account_password"><?php echo $lang['password']; ?></label>
				<input type="password" class="form-control" name="account_password" placeholder="<?php echo $lang['password']; ?>" <?php if(isset($_SESSION['account_password'])) echo 'value="' . $_SESSION['account_password'] . '"'; ?>>
				<p class="help-block"><?php echo $lang['passwordDesc']; ?></p>
			</div>
			<div class="form-group">
				<label for="account_passwordConfirmation"><?php echo $lang['password']; ?> (confirmation)</label>
				<input type="password" class="form-control" name="account_passwordConfirmation" placeholder="<?php echo $lang['password']; ?>" <?php if(isset($_SESSION['account_password'])) echo 'value="' . $_SESSION['account_password'] . '"'; ?>>
			</div>
			<div class="form-group">
				<label for="account_email"><?php echo $lang['email']; ?></label>
				<input type="email" class="form-control" name="account_email" placeholder="<?php echo $lang['email']; ?>" <?php if(isset($_SESSION['account_email'])) echo 'value="' . $_SESSION['account_email'] . '"'; ?>>
				<p class="help-block"><?php echo $lang['emailDesc']; ?></p>
			</div>
			
			<br />
			
			<a href="step5.php" class="btn btn-primary"><?php echo $lang['goBack']; ?></a>
			<button type="submit" class="btn btn-info pull-right"><?php echo $lang['continue']; ?></button>
		</form>
	</div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>
