<?php
session_start();

//We check if step 6 has been completed
if(!isset($_SESSION['installation_step6'])) {
	header('location: step6.php');
	exit();
} 

include("lang/" . $_SESSION['app_language'] . ".php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$_SESSION['installation_step7'] = true;
		
	header('location: install.php');
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $lang['readyToInstall']; ?> &bull; <?php echo $lang['setupWizard']; ?> &bull; Expressif</title>
	
	<?php include("includes/header.php"); ?>
	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</head>
<body>

<?php include("includes/body.php"); ?>
  
<div class="container">
	<div class="row">
		<div class="page-header">
			<h1><small><?php echo $lang['readyToInstall']; ?></small></h1>
		</div>
		
		<p><?php echo $lang['softwareReadyToInstall']; ?></p>
		
		<br />
		
		<p><?php echo $lang['installDirWillBeRemoved']; ?></p>
		
		<br />
		
		<form action="step7.php" method="post">
			<a href="step6.php" class="btn btn-primary"><?php echo $lang['goBack']; ?></a>
			<button type="submit" class="btn btn-success pull-right"><?php echo $lang['install']; ?></button>
		</form>
	</div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>
