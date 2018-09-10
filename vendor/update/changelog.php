<?PHP
$updatesDataServerUrlPrefix = 'http://expressif.net/zirkon/';
$filenameAllReleaseVersions = $updatesDataServerUrlPrefix. 'current-release-versions.txt';
$directoryAllUpdatePackages = $updatesDataServerUrlPrefix. 'update-packages/';

if(version_compare(phpversion(), '4') < 0)
{
	die(i18n('PHP 4 or greater is required.'));
}
@ini_set('max_execution_time',0);
@set_time_limit(0); // Cannot set time limit in safe mode
$versionList = @file($filenameAllReleaseVersions) 
	or die ('ERROR - Could Not Read New-Versions. Operation Aborted.</p>');
if ( count($versionList) > 0)
{
	$output = '';
	arsort($versionList);
	foreach ( $versionList as $actualVersion )
	{
		if ( $info = @file($directoryAllUpdatePackages.trim($actualVersion).'.txt') )
		{
			$output .= '				<div class="timeline-item">
					<span class="ui label large version">v '.trim($actualVersion).'</span>
					<div class="timeline-content">
						<div class="ui list large">
';
			$output .= '							<p><b>'.trim(array_shift($info)).'</b></p>'."\n";
			foreach ( $info as $line )
			{
				list($type,$text) = explode('-',$line,2);
				switch(strtolower(trim($type))) { 
					case 'add': $color = 'green';  break; 
					case 'fix': $color = 'yellow'; break; 
					case 'imp': $color = 'blue';   break; 
					case 'del': $color = 'red';    break; 
					default   : $color = ''; 
				}
				$output .= '							<div class="ui item">
								<div class="ui tiny label '.$color.'">'.trim($type).'</div>
								'.rtrim($text, "\x00..\x1F").'
							</div>
';
			}
			$output .= '						</div>
					</div>
				</div>
';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Changelog</title>
	<link rel="shortcut icon" type="image/x-icon" href="vendor/update/resources/img/favicon.ico" />
	<link rel="icon" type="image/x-icon" href="vendor/update/resources/img/favicon.ico" />
	<link rel="stylesheet" href="vendor/update/resources/css/libs/semantic.min.css">
	<link rel="stylesheet" href="vendor/update/resources/css/main.min.css">
	<script src="vendor/update/resources/js/libs/jquery.min.js"></script>
	<script src="vendor/update/resources/js/libs/smoothScroll.js"></script>
	<script src="vendor/update/resources/js/libs/semantic.min.js"></script>
	<script src="vendor/update/resources/js/main.js"></script>
</head>
<body class="changelog">
	<header class="main-header">
		<h1 class="ui header aligned center">Changelog</h1>
	</header>
	<div class="ui page grid">
		<div class="column">
			<div class="main-timeline">
<?PHP echo $output; ?>
			</div>
			<h5 class="ui header aligned center hidden-print">
				<a href="update.php">Start →</a>
			</h5>
		</div>
	</div>
</body>
</html>