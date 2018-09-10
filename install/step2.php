<?php
session_start();

//We check if step 1 has been completed
if(!isset($_SESSION['installation_step1'])) {
	header('location: index.php');
	exit();
}

include("lang/" . $_SESSION['app_language'] . ".php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['license'])) {
		$_SESSION['installation_step2'] = true;
		
		header('location: step3.php');
		exit();
	} else {
		$_SESSION['installation_error'] = $lang['licenseTermsMustBeAccepted'];
		
		//If step 2 has been completed before and is not valid anymore with the new information provided, we block all the next steps
		if(isset($_SESSION['installation_step2']))
			unset($_SESSION['installation_step2']);
		if(isset($_SESSION['installation_step3']))
			unset($_SESSION['installation_step3']);
		if(isset($_SESSION['installation_step4']))
			unset($_SESSION['installation_step4']);
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
	<title><?php echo $lang['licenseAgreement']; ?> &bull; <?php echo $lang['setupWizard']; ?> &bull; Expressif</title>
	
	<?php include("includes/header.php"); ?>
	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</head>
<body>

<?php include("includes/body.php"); ?>
  
<div id="wrap"> 
	<div class="container">
		<div class="row">
			<div class="page-header">
				<h1><small><?php echo $lang['licenseTerms']; ?></small></h1>
			</div>
			
			<?php include("errors.php"); ?>
			
			<p><?php echo $lang['step2Desc']; ?></p>
			<br />
			<textarea class="form-control" rows="20" readonly="readonly">
			<?php include("license.txt"); ?>
			</textarea>
			<br /><br />
			<form action="step2.php" method="post">
				<label><?php echo $lang['IAgreeWithLicenseTerms']; ?></label>
				<p class="pull-right"><input type="checkbox" checked data-toggle="toggle" data-on="Yes" data-off="No" name="license"></p>
				<br /><br /><br />
				<a href="index.php" class="btn btn-primary"><?php echo $lang['goBack']; ?></a>
				<button type="submit" class="btn btn-info pull-right"><?php echo $lang['continue']; ?></button>
			</form>
		</div>
	</div>
	<div id="push"></div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>
