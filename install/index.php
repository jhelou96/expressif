<?php
session_start();

include("lang/en.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$_SESSION['app_language'] = $_POST['installation_language'];
	$_SESSION['installation_step1'] = true; //Step 1 completed
	
	header('location: step2.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Setup wizard &bull; Expressif</title>
	
	<?php include("includes/header.php"); ?>
	
	</head>
<body>

<?php include("includes/body.php"); ?>

<div class="container">
	<div class="row">
		<div class="page-header">
			<h1><small>Welcome to the Expressif setup wizard !</small></h1>
		</div>
		<p>Thank you for trusting Expressif in the creation of your website.</p>
		<p>The installation is automated and will only take a couple of minutes. Please, start by chosing your language to proceed</p>
		<br />
		<form action="index.php" method="post">
			<div class="form-group">
				<label for="installation_language">Language</label>
				<select class="form-control" id="installation_language" name="installation_language">
					<option value="en">English</option>
					<option value="fr">French</option>
				</select>
			</div>
			<br />
			<button type="submit" class="btn btn-primary pull-right" >Start</button>
		</form>
	</div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>

<?php include("errors.php"); ?>