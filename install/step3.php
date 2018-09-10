<?php
session_start();

//We check if step 2 has been completed
if(!isset($_SESSION['installation_step2'])) {
	header('location: step2.php');
	exit();
} 

include("lang/" . $_SESSION['app_language'] . ".php");

//We check server requirements 
function findSQLVersion() { 
	$output = shell_exec('mysql -V'); 
	preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
	
	return $version[0]; 
} 

if(version_compare(PHP_VERSION, '5.5.0', '>='))
	$php = '<span class="label label-success">' . PHP_VERSION  . '</span>'; 
else {
	$php = '<span class="label label-danger">' . PHP_VERSION . '</span>'; 
	$_SESSION['installation_error'] = $lang['PHPError'];
}
if(version_compare(findSQLVersion(), '5.7', '>=')) 
	$mysql = '<span class="label label-success">' . findSQLVersion()  . '</span>'; 
else {
	$mysql = '<span class="label label-danger">' . findSQLVersion() . '</span>'; 
	$_SESSION['installation_error'] = $lang['MySQLError'];
}
if(class_exists('PDO')) 
	$pdo = '<span class="label label-success">Enabled</span>'; 
else {
	$pdo = '<span class="label label-danger">Disabled</span>'; 
	$_SESSION['installation_error'] = $lang['PDOError'];
}
if(is_writable('../config')) 
	$configWritable = '<span class="label label-success">' . substr(sprintf('%o', fileperms('../config')), -4) . '</span>'; 
else {
	$configWritable = '<span class="label label-danger">' . substr(sprintf('%o', fileperms('../config')), -4) . '</span>'; 
	$_SESSION['installation_error'] = $lang['ConfigDirError'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(!isset($_SESSION['installation_error'])) {
		$_SESSION['installation_step3'] = true;
		
		header('location: step4.php');
		exit();
	} else {
		//If step 3 has been completed before and is not valid anymore with the new information provided, we block all the next steps
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
	<title><?php echo $lang['serverRequirements']; ?> &bull; <?php echo $lang['setupWizard']; ?> &bull; Expressif</title>
	
	<?php include("includes/header.php"); ?>
	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</head>
<body>

<?php include("includes/body.php"); ?>
  
<div class="container">
	<div class="row">
		<div class="page-header">
			<h1><small><?php echo $lang['serverRequirements']; ?></small></h1>
		</div>
		
		<?php 
		if(!isset($_SESSION['installation_error'])) {
			echo '
				<div class="alert alert-success alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>' . $lang['success'] . '!</strong> ' . $lang['serverRequirementsSuccess'] . '
				</div>
			';
		}
		?>
		
		<?php include("errors.php"); ?>
		
		<p><?php echo $lang['step3Desc']; ?></p>
		
		<br />
		
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:70%"><?php echo $lang['extension']; ?></th>
					<th><?php echo $lang['minRequired']; ?></th>
					<th><?php echo $lang['server']; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>PHP</td>
					<td>5.5.0+</td>
					<td><?php echo $php; ?></td>
				</tr>
				<tr>
					<td>MySQL</td>
					<td>5.5+</td>
					<td><?php echo $mysql; ?></td>
				</tr>
				<tr>
					<td>PDO</td>
					<td><?php echo $lang['enabled']; ?></td>
					<td><?php echo $pdo; ?></td>
				</tr>
				<tr>
					<td><?php echo $lang['folder']; ?> /config</td>
					<td><?php echo $lang['writable']; ?></td>
					<td><?php echo $configWritable; ?></td>
				</tr>
			</tbody>
		</table>
		
		<form action="step3.php" method="post">
			<a href="step2.php" class="btn btn-primary"><?php echo $lang['goBack']; ?></a>
			<button type="submit" class="btn btn-info pull-right"><?php echo $lang['continue']; ?></button>
		</form>
	</div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>
