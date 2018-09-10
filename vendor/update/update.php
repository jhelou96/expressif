<?PHP

// ------- COLORS FOR THE UI
	$grey   = ' style="border-left:7px solid #bcbcbc;padding-left:12px;list-style-type:none"';
	$red    = ' style="border-left:7px solid #dd3d36;padding-left:12px;list-style-type:none"';
	$orange = ' style="border-left:7px solid #ffba00;padding-left:12px;list-style-type:none"';
	$green  = ' style="border-left:7px solid #7ad03a;padding-left:12px;list-style-type:none"';

// ------- SETUP ist a must have ;) 
	(@include_once('setup.php')) OR die('<tt><p'.$red.'><b>FATAL ERROR</b><br>Failed opening required setup.php&ldquo;</p></tt>');

// ------- VAR FOR TEXT IN YOUR LANGUAGE
	@include_once('config/Config.php');
	@include_once(config\Config::getLang() . '.php');

// ------- IF PECL IS NOT INSTALLED and YOU USE LINUX
	@include_once('zipFallbackLinux.php');
	#include_once('pclzip.lib.php');

/* ------------------------------------------------------------------------- */

// Path from this script
$localScriptsPath = ''; // don't forget the slash at the end

// HTML HEADER inkl. CSS
print '<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>'.i18n('title').'</title>
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<style>/* ORANGE 000 #ff9632  */
			::-moz-selection{color:#fff;text-shadow:none;background:#80ba42;} /* GREEN */
			::selection{color:#fff;text-shadow:none;background:#80ba42;} /* GREEN */
			*{font-family:monospace;}
			pre{width:100%;max-width:100%;font-family:Consolas,Lucida Console,monospace;background-color:#f4f4f4;
				baupdate.php?check_for_updates=trueckground-image:-webkit-gradient(linear,50% 0%,50% 100%,color-stop(50%,#f4f4f4),color-stop(50%,#e5e5e5));
				background-image:-webkit-linear-gradient(#f4f4f4 50%,#e5e5e5 50%);
				background-image:-webkit-gradient(linear,left top,left bottom,color-stop(50%,#f4f4f4),color-stop(50%,#e5e5e5));
				background-image:-webkit-linear-gradient(#f4f4f4 50%,#e5e5e5 50%);background-image:linear-gradient(#f4f4f4 50%,#e5e5e5 50%);
				-webkit-background-size:38px 38px;background-size:38px 38px;border:1px solid #c5c5c5;line-height:19px;
				overflow-y:hidden;overflow-x:auto;padding:0 0 0 4px;font-size:small;}
			@media only screen and (min-width:760px){pre{width:616px;max-width:616px}}
			a{margin-left:4px;color:#328efe;padding:7px;}
			a:hover{text-decoration:none;color:white;background-color:#328efe;padding:7px;}
		</style>
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body style="background-color:white;">
		<h1>'.i18n('DYNAMIC UPDATE SYSTEM').'</h1>
';

print_message('', '&raquo; <a href="backend">'.i18n('Return to panel').'</a>');


// LINK to CHANGELOG
print_message('', '&raquo; <a href="changelog.php">'.i18n('Changelog').'</a>');

// Check PHP Version
if ( version_compare(phpversion(), '4.1') < 0 )
{
	die(print_message(i18n('ERROR'),i18n('PHP 4.1 or greater is required.'),$color='red'));
}
if ( !isset($updatesDataServerUrlPrefix) )
{
	die(print_message(i18n('ERROR'),i18n('Set $updatesDataServerUrlPrefix in setup.php'),$color='red'));
}
if ( !isset($filenameAllReleaseVersions) )
{
	die(print_message(i18n('ERROR'),i18n('Set $filenameAllReleaseVersions in setup.php'),$color='red'));
}
if ( !isset($directoryAllUpdatePackages) )
{
	die(print_message(i18n('ERROR'),i18n('Set $directoryAllUpdatePackages in setup.php'),$color='red'));
}
if ( !isset($localVersionFile) )
{
	die(print_message(i18n('ERROR'),i18n('Set $localVersionFile in setup.php'),$color='red'));
}
if ( !isset($localDownloadDir) )
{
	die(print_message(i18n('ERROR'),i18n('Set $localDownloadDir in setup.php'),$color='red'));
}

// We don't want a timeout
@set_time_limit(0); // Cannot set time limit in safe mode
@ini_set('max_execution_time',0); // so we also try this!
@ini_set('memory_limit', '32M');

// Read local installed Version
if ( !file_exists($localScriptsPath.$localVersionFile) )
{
	$currentVersion = 1;
}
else
if(	$currentVersion = @file_get_contents($localScriptsPath.$localVersionFile) )
{
	$currentVersion = trim($currentVersion);
}
else
{
	print_message(i18n('ERROR'),i18n('Could Not Read Current-Version. Operation Aborted'),$color='red'); 
	die('</body></html>');
}

// Read Update Versions
if ( $getVersions = @file_get_contents($updatesDataServerUrlPrefix.$filenameAllReleaseVersions) )
{
	$getVersions = trim($getVersions);
}
else
{
	print_message(i18n('ERROR'),i18n('Could Not Read New-Versions. Operation Aborted'),$color='red');
	die('</body></html>');
}

// Print Infos about the available updates
if ( $getVersions != '' and $currentVersion != '' )
{
	// OUTPUT UI Info and STEP 1
	print_message(i18n('CURRENT VERSION'),$currentVersion,$color='grey');
	print_message(i18n('WARNING'),i18n('The upgrade process will affect all files and folders included in the main script installation.').'<br>'.
		i18n('This includes all the core files used to run the script.').'<br>'.
		i18n('If you have made any modifications to those files, your changes will be lost.'),$color='red');
	print_message(i18n('IMPORTANT'),i18n('Before you perform the update, make sure to backup your database and all files!'),$color='orange');
	$step = 1;
	print_message(i18n('STEP').' '.$step,i18n('Reading Current Releases List'),$color='grey');

	// Use each available Update
	$versionList = explode("\n", $getVersions);
	sort ( $versionList );
	foreach ( $versionList as $actualVersion )
	{
		$actualVersion = trim($actualVersion);
		if ( $actualVersion > $currentVersion ) 
		{
			$step++;
			print_message(i18n('STEP').' '.$step,i18n('New Update Found &mdash; Version').' '.$actualVersion,$color='grey');
			
			// Show Info-File if exist
			if ( $info = @file_get_contents($updatesDataServerUrlPrefix.$directoryAllUpdatePackages.$actualVersion.'.txt') )
			{
				print_message(i18n('What\'s New'),'<pre>'.$info.'</pre>',$color='grey');
			}
			$found = true;
			
			// Download The ZIP-File If We Do Not Have It
			$actualVersionStrlen = mb_strlen(@file_get_contents($localScriptsPath.$localDownloadDir.$actualVersion.'.zip')); // mb_strlen(in case file contains unicode)
			if ( !is_file($localScriptsPath.$localDownloadDir.$actualVersion.'.zip') )
			{
				$step++;
				print_message(i18n('STEP').' '.$step,i18n('Downloading New Update'),$color='grey');
				download_zip($actualVersion);
				$step++;
				print_message(i18n('STEP').' '.$step,i18n('Update Downloaded And Saved'),$color='grey');
			}
			else // Download The ZIP-File If It Is Outdatet - mb_strlen(in case file contains unicode)
			if ( $actualVersionStrlen != mb_strlen(@file_get_contents($updatesDataServerUrlPrefix.$directoryAllUpdatePackages.$actualVersion.'.zip')) )
			{
				$step++;
				print_message(i18n('STEP').' '.$step,i18n('Already Downloaded File Is Outdatet'),$color='grey');
				download_zip($actualVersion);
				$step++;
				print_message(i18n('STEP').' '.$step,i18n('File Is Downloaded And Saved New'),$color='grey');
			}
			else // Show Filesize
			{
				$step++;
				print_message(i18n('STEP').' '.$step,i18n('Update Already Downloaded'),$color='grey');
				$step++;
				print_message(i18n('STEP').' '.$step,i18n('Filesize').' '.human_filesize($actualVersionStrlen).'</p>',$color='grey');
			}
			
			// If User want to UPDATE
			if ( isset($_GET['doUpdate']) and $_GET['doUpdate'] == true ) 
			{
				
				// Open the ZIP-File
				$zipHandle = zip_open($localScriptsPath.$localDownloadDir.$actualVersion.'.zip');
				$step++;
				print_message(i18n('STEP').' '.$step,i18n('Peak Memory Usage').' '.human_filesize(memory_get_peak_usage(TRUE)).'</p>',$color='grey');
				if (!is_resource($zipHandle))
				{
					print_message(i18n('ERROR'),i18n('Could Not Read File').' '.$actualVersion.'.zip&ldquo;. '.i18n('Operation Aborted'),$color='red');
					die('</body></html>');
				}
				
				// Read each File from ZIP
				print '<ul'.$grey.'><p><b>'.i18n('DO').'</b></p>';
				$versionTextFileWritten = FALSE;
				while ( $aF = zip_read($zipHandle) ) 
				{
					$thisFileName = zip_entry_name($aF);
					$thisFileDir = trim(dirname($thisFileName));
					
					// Continue if its not a file
					if ( substr($thisFileName,-1,1) == '/' ) 
					{
						continue;
					}
					
					// Make the local download directory if we need to...
					if ( !is_dir ($localScriptsPath.$thisFileDir) )
					{
						if(@mkdir ($localScriptsPath.$thisFileDir, 0777, true ) )
						{
							print_message(i18n('CREATED'),i18n('Dir').' '.$thisFileDir.'&ldquo;',$color='orange');
						}
						else
						{
							print_message(i18n('ERROR'),i18n('Could Not Create Dir').' '.$thisFileDir.'&ldquo;. '.i18n('Operation Aborted'),$color='red');
							die('</body></html>');
						}
					}
					
					// Overwrite the file
					if ( !is_dir($localScriptsPath.$thisFileName) ) 
					{
						$contents = zip_entry_read($aF, zip_entry_filesize($aF));
						$contents = str_replace("\r\n", "\n", $contents);
						$updateThis = '';
						
						// If we need to run commands - include upgrade.php
						if ( $thisFileName == 'upgrade.php' )
						{
							$upgradeExec = @fopen($localScriptsPath.'upgrade.php','wb');
							if ( @fwrite($upgradeExec, $contents) )
							{
								fclose($upgradeExec);
								print '<ul'.$orange.'>';
								print_message(i18n('EXECUTED'),i18n('File').' upgrade.php&ldquo;',$color='');
								include($localScriptsPath.'upgrade.php');
								unlink($localScriptsPath.'upgrade.php');
								print '</ul>';
							}
							else
							{
								print_message(i18n('ERROR'),i18n('Could Not Execute File upgrade.php&ldquo;. Operation Aborted'),$color='red');
								die('</body></html>');
							}
						}
						else // IF IS IN ZIP: Write back now local installed new Version-Number
						{
							if ( $thisFileName == $localVersionFile )
							{
								$versionTextFileWritten = TRUE;
							}
							$updateThis = @fopen($localScriptsPath.$thisFileName, 'wb');
							if ( @fwrite($updateThis, $contents) )
							{
								fclose($updateThis);
								unset($contents);
								print_message(i18n('UPDATED'),i18n('File').' '.$thisFileName.'&ldquo;',$color='green');
							}
							else
							{
								print_message(i18n('ERROR'),i18n('Could Not Create File').' '.$thisFileName.'&ldquo;. '.i18n('Operation Aborted'),$color='red');
								die('</body></html>');
							}
						}
					}
				}
				print '</ul>';
				$updated = TRUE;
			}
			else // Ask User: "want to update"? 
			{
				print_message(i18n('OK'),i18n('Update Ready'),$color='green');
				print_message('', '&raquo; <a href="?doUpdate=true">'.i18n('Install Now?').'</a>');
			}
			break;
		}
	}
	
	// Write back now local installed new Version-Number IF IT WAS not IN THE ZIP
	if ( isset($updated) and $updated == true )
	{
		if($versionTextFileWritten == FALSE)
		{
			$f = @fopen($localScriptsPath.$localVersionFile, 'wb');
			if ( @fwrite($f, $actualVersion) )
			{
				fclose($f);
				print_message(i18n('UPDATED'),i18n('File').' '.$localVersionFile.'&ldquo; ('.i18n('not downloaded').')',$color='green');
			}
			else
			{
				print_message(i18n('ERROR'),i18n('Could Not Create File').' '.$localVersionFile.'&ldquo;. '.i18n('Operation Aborted'),$color='red');
				die('</body></html>');
			}
		}
		print_message(i18n('READY'),i18n('Script Updated To Version').' '.$actualVersion,$color='green');
		print_message('', '&raquo; <a href="?check_for_updates=true">'.i18n('Check For Updates?').'</a>');
	}
	else // Print Info: You now use the latest Version
	if ( !isset($found) or $found != true ) 
	{
		print_message(i18n('INFO'),i18n('The Newest Version Of The Script Is Version').' '.$currentVersion,$color='green');
		print_message(i18n('OK'),i18n('This Is The Latest Version!'),$color='green');
		print_message('', '&raquo; <a href="?check_for_updates=true">'.i18n('Check For Updates?').'</a>');
	}
	

}
else if ($getVersions == '' OR $currentVersion == '') // ERROR if Update-Infos oder Current-Version not found
{ 
	print_message(i18n('ERROR'),i18n('Could Not Find Latest Releases. Operation Aborted'),$color='red');
}
die('</body></html>');

/* ------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------- */

// FUNCTION: copy Update ZIP from Webserver to local File-System
function download_zip($actualVersion)
{
	global $red, $updatesDataServerUrlPrefix, $directoryAllUpdatePackages, $localScriptsPath, $localDownloadDir, $i18n, $orange, $localScriptsPath, $step;
	if ( $newUpdate = @file_get_contents($updatesDataServerUrlPrefix.$directoryAllUpdatePackages.$actualVersion.'.zip') )
	{
		$filesize = mb_strlen($newUpdate); // mb_strlen(in case file contains unicode)
		$step++;
		print_message(i18n('STEP').' '.$step,i18n('Download').' '.i18n('Filesize').' '.human_filesize($filesize).'</p>',$color='grey');
	}
	else
	{
		print_message(i18n('ERROR'),i18n('Could Not Read File').' '.$actualVersion.'.zip&ldquo;. '.i18n('Operation Aborted'),$color='red');
		die('</body></html>');
	}
	if ( !is_dir( $localScriptsPath.$localDownloadDir ) ) 
	{
		if ( @mkdir ( $localScriptsPath.$localDownloadDir, 0777, true ) )
		{
			$step++;
			print_message(i18n('STEP').' '.$step,'<b>'.i18n('CREATED').'</b> '.i18n('Dir').' '.$localDownloadDir.'&ldquo;</p>',$color='orange');
		}
		else
		{
			print_message(i18n('ERROR'),i18n('Could Not Create Dir').' '.$localDownloadDir.'&ldquo;. '.i18n('Operation Aborted'),$color='red');
			die('</body></html>');
		}
	}
	$dlHandler = @fopen( $localScriptsPath.$localDownloadDir.$actualVersion.'.zip', 'wb');
	if ( !@fwrite($dlHandler, $newUpdate) ) 
	{
		print_message(i18n('ERROR'),i18n('Could Not Save New Update. Operation Aborted'),$color='red');
		die('</body></html>');
	}
	fclose($dlHandler);
}

// FUNCTION 4 upgrade.php: Delete dir whith all Files and Sub-Dirs in local File-System
function del_tree($dir)
{
	global $localScriptsPath;
	
	$dir = $localScriptsPath . $dir;
	
	global $red, $i18n;
	if(empty($dir) OR is_file($dir) OR !is_dir($dir))
	{
		print_message(i18n('ERROR'),i18n('Could Not Delete File').' '.$dir.'&ldquo;',$color='red');
	}
	else
	{
		$files = array_diff(scandir($dir), array('.', '..')); 
		foreach ($files as $file)
		{ 
			if ( is_file("$dir/$file") ) 
			{
				unlink("$dir/$file");
				print_message(i18n('DELETE'),i18n('File').' '.$file.'&ldquo;',$color='red');
			}
			else
			{
				del_tree("$dir/$file");
			} 
		}
		print_message(i18n('DELETE'),i18n('Dir').' '.$dir.'&ldquo;',$color='red');
		return rmdir($dir);
	}
}

// FUNCTION: Print UI Messages
function print_message($headline,$message,$color='')
{
	switch ($color) {
		case 'red':
			global $red;
			$color = $red;
			break;
		case 'orange':
			global $orange;
			$color = $orange;
			break;
		case 'green':
			global $green;
			$color = $green;
			break;
		case '':
			$color = '';
			break;
		default:
			global $grey;
			$color = $grey;
			break;
	}
	print '<p'.$color.'><b>'.$headline.'</b><br>'.$message.'</p>';
}

// FUNCTION: Use extern i18n.php File for Translations of this Script
function i18n($string, $replaceArray = array(), $default = false)
{
	global $i18n,$automaticallyScrollToTheBottomOfThePage;
	if(is_array($replaceArray) and count($replaceArray) > 0){
		$i18n[$string] = strReplaceAssoc($replaceArray, $i18n[$string]);
	}
	if (isset($automaticallyScrollToTheBottomOfThePage) and $automaticallyScrollToTheBottomOfThePage == TRUE and $string != 'title')
	{
		$scroll = '<script>window.setTimeout("scrollBy(0,1000)", 500);</script>';
	}
	else
	{
		$scroll = '';
	}
	return isset($i18n[$string]) ? $i18n[$string].$scroll : ($default ? $default.$scroll : $string.$scroll);
	#return isset($i18n[$string]) ? '<b style="color:red">'.$i18n[$string].'</b>'.$scroll : ($default ? $default.$scroll : $string.$scroll); // i18n DEBUG (show everything red from i18n.php)
}

// FUNCTION: Extremely simple function to get human filesize
function human_filesize($bytes, $decimals = 2)
{
	$unit = array('B','KB','MB','GB','TB','PB');
    return @round($bytes/pow(1024,($i=floor(log($bytes,1024)))),$decimals).' '.$unit[$i];
}

/* ------- EOF ------- */
// HINT: Variables = carmelCase - Functions = under_score
