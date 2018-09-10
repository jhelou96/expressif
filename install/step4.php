<?php
session_start();

//We check if step 3 has been completed
if(!isset($_SESSION['installation_step3'])) {
	header('location: step3.php');
	exit();
} 

include("lang/" . $_SESSION['app_language'] . ".php");

//List of timezones
$regions   = array(
	'Africa' => DateTimeZone::AFRICA,
	'America' => DateTimeZone::AMERICA,
	'Antarctica' => DateTimeZone::ANTARCTICA,
	'Asia' => DateTimeZone::ASIA,
	'Atlantic' => DateTimeZone::ATLANTIC,
	'Europe' => DateTimeZone::EUROPE,
	'Indian' => DateTimeZone::INDIAN,
	'Pacific' => DateTimeZone::PACIFIC
);
$timezones = array();
foreach ($regions as $name => $mask) {
	$zones = DateTimeZone::listIdentifiers($mask);
	foreach ($zones as $timezone) {
		// Lets sample the time there right now
		$time = new DateTime(NULL, new DateTimeZone($timezone));
		// Us dumb Americans can't handle millitary time
		$ampm = $time->format('H') > 12 ? ' (' . $time->format('g:i a') . ')' : '';
		// Remove region name and add a sample time
		$timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
	}
}

//Application path
if(strlen(dirname($_SERVER['SCRIPT_NAME'])) == 1) //If path only contains "/", we don't take it into account
	$path = "";
else
	$path = str_replace("/install", "", dirname($_SERVER['SCRIPT_NAME'])); //We don't take the install directory into account in the path

//Website URL
$url = "http://" . $_SERVER['SERVER_NAME'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$error = false;
	
	//We check if there is any error in the inputs submitted
	if(empty($_POST['app_url']))
		$error = true;
	if(empty($_POST['app_name']))
		$error = true;
	if(empty($_POST['app_timezone']))
		$error = true;
	
	if(!$error) {
		$_SESSION['app_url'] = htmlspecialchars($_POST['app_url']);
		$_SESSION['app_path'] = htmlspecialchars($_POST['app_path']);
		$_SESSION['app_name'] = htmlspecialchars($_POST['app_name']);
		$_SESSION['app_timezone'] = htmlspecialchars($_POST['app_timezone']);
		
		$_SESSION['installation_step4'] = true;
		
		header('location: step5.php');
		exit();
	} else {
		$_SESSION['installation_error'] = $lang['invalidInputsSubmitted'];
		
		//If step 4 has been completed before and is not valid anymore with the new information provided, we block all the next steps
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
	<title><?php echo $lang['appSettings']; ?> &bull; <?php echo $lang['setupWizard']; ?> &bull; Expressif</title>
	
	<?php include("includes/header.php"); ?>
	
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</head>
<body>

<?php include("includes/body.php"); ?>
  
<div class="container">
	<div class="row">
		<div class="page-header">
			<h1><small><?php echo $lang['appSettings']; ?></small></h1>
		</div>
		
		<?php include("errors.php"); ?>
		
		<p><?php echo $lang['step4Desc']; ?></p>
		
		<br />
		
		<form action="step4.php" method="post">
			<div class="form-group">
				<label for="app_url"><?php echo $lang['websiteURL']; ?></label>
				<input type="url" class="form-control" name="app_url" placeholder="http://example.com" <?php if(isset($_SESSION['app_url'])) echo 'value="' . $_SESSION['app_url'] . '"'; else echo 'value="' . $url . '"'; ?>>
			</div>
			<div class="form-group">
				<label for="app_path"><?php echo $lang['appPath']; ?></label>
				<input type="text" class="form-control" name="app_path" placeholder="<?php echo $lang['appPath']; ?>" <?php if(isset($_SESSION['app_path'])) echo 'value="' . $_SESSION['app_path'] . '"'; else echo 'value="' . $path . '"'; ?>>
				<p class="help-block"><?php echo $lang['appPathDesc']; ?></p>
			</div>
			<div class="form-group">
				<label for="app_url"><?php echo $lang['websiteName']; ?></label>
				<input type="text" class="form-control" name="app_name" placeholder="<?php echo $lang['websiteName']; ?>" <?php if(isset($_SESSION['app_name'])) echo 'value="' . $_SESSION['app_name'] . '"'; ?>>
			</div>
			<div class="form-group">
				<label for="app_url"><?php echo $lang['timezone']; ?></label>
				<select class="form-control" name="app_timezone">
				
					<?php
					foreach($timezones as $region=>$list) {
						echo '<optgroup label="' . $region . '">';
						
						foreach($list as $timezone=>$name) 
							echo '<option value="' . $timezone . '" ' . ((isset($_SESSION['app_timezone']) AND $_SESSION['app_timezone'] == $timezone) ? 'selected' : '') . '>' . $name . '</option>';
						
						echo '</optgroup>';
					}
					?>
				
				</select>
			</div>
			
			<br />
			
			<a href="step3.php" class="btn btn-primary"><?php echo $lang['goBack']; ?></a>
			<button type="submit" class="btn btn-info pull-right"><?php echo $lang['continue']; ?></button>
		</form>
	</div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>
