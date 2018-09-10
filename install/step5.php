<?php
session_start();

//We check if step 4 has been completed
if(!isset($_SESSION['installation_step4'])) {
	header('location: step4.php');
	exit();
} 

include("lang/" . $_SESSION['app_language'] . ".php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$hostname = htmlspecialchars($_POST['db_host']);
	$user = htmlspecialchars($_POST['db_user']);
	$password = htmlspecialchars($_POST['db_password']);
	$name = htmlspecialchars($_POST['db_name']);
	$prefix = htmlspecialchars($_POST['db_prefix']);
	
	//We check if the DB information provided are valid
	try {
		$dbh = new PDO('mysql:host=' . $hostname . ';dbname=' . $name, $user, $password);
		
		if(strlen($prefix) >= 2 AND substr($prefix, -1) == '_') {	
			$_SESSION['db_host'] = $hostname;
			$_SESSION['db_user'] = $user;
			$_SESSION['db_password'] = $password;
			$_SESSION['db_name'] = $name;
			$_SESSION['db_prefix'] = $prefix;
			
			$_SESSION['installation_step5'] = true;
			
			header('location: step6.php');
			exit();
		} else {
			$_SESSION['installation_error'] = $lang['tablesPrefixError'];
			
			//If step 5 has been completed before and is not valid anymore with the new information provided, we block all the next steps
			if(isset($_SESSION['installation_step5']))
				unset($_SESSION['installation_step5']);
			if(isset($_SESSION['installation_step6']))
				unset($_SESSION['installation_step6']);
			if(isset($_SESSION['installation_step7']))
				unset($_SESSION['installation_step7']);
		}
	} catch(PDOException $e) {
		$_SESSION['installation_error'] = $lang['databaseConnectionFailed'];
		
		//If step 5 has been completed before and is not valid anymore with the new information provided, we block all the next steps
		if(isset($_SESSION['installation_step5']))
			unset($_SESSION['installation_step5']);
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
	<title><?php echo $lang['databaseSettings']; ?> &bull; <?php echo $lang['setupWizard']; ?> &bull; Expressif</title>
	
	<?php include("includes/header.php"); ?>
	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</head>
<body>

<?php include("includes/body.php"); ?>
  
<div class="container">
	<div class="row">
		<div class="page-header">
			<h1><small><?php echo $lang['databaseSettings']; ?></small></h1>
		</div>
		
		<?php include("errors.php"); ?>
		
		<p><?php echo $lang['step5Desc']; ?></p>
		
		<br />
		
		<form action="step5.php" method="post">
			<div class="form-group">
				<label for="db_host"><?php echo $lang['dbHost']; ?></label>
				<input type="text" class="form-control" name="db_host" placeholder="<?php echo $lang['dbHost']; ?>" <?php if(isset($_SESSION['db_host'])) echo 'value="' . $_SESSION['db_host'] . '"'; else echo 'value="localhost"'; ?>>
			</div>
			<div class="form-group">
				<label for="db_user"><?php echo $lang['dbUser']; ?></label>
				<input type="text" class="form-control" name="db_user" placeholder="<?php echo $lang['dbUser']; ?>" <?php if(isset($_SESSION['db_user'])) echo 'value="' . $_SESSION['db_user'] . '"'; ?>>
			</div>
			<div class="form-group">
				<label for="db_password"><?php echo $lang['dbPassword']; ?></label>
				<input type="password" class="form-control" name="db_password" placeholder="<?php echo $lang['dbPassword']; ?>" <?php if(isset($_SESSION['db_password'])) echo 'value="' . $_SESSION['db_password'] . '"'; ?>>
			</div>
			<div class="form-group">
				<label for="db_name"><?php echo $lang['dbName']; ?></label>
				<input type="text" class="form-control" name="db_name" placeholder="<?php echo $lang['dbName']; ?>" <?php if(isset($_SESSION['db_name'])) echo 'value="' . $_SESSION['db_name'] . '"'; ?>>
			</div>
			<div class="form-group">
				<label for="db_prefix"><?php echo $lang['dbPrefix']; ?></label>
				<input type="text" class="form-control" name="db_prefix" placeholder="<?php echo $lang['dbPrefix']; ?>" <?php if(isset($_SESSION['db_prefix'])) echo 'value="' . $_SESSION['db_prefix'] . '"'; else echo 'value="expressif_"'; ?>>
			</div>
			
			<br />
			
			<a href="step4.php" class="btn btn-primary"><?php echo $lang['goBack']; ?></a>
			<button type="submit" class="btn btn-info pull-right"><?php echo $lang['continue']; ?></button>
		</form>
	</div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>
