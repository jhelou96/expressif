<?php
session_start();

//We check if step 7 has been completed
if(!isset($_SESSION['installation_step7'])) {
	header('location: step7.php');
	exit();
} 

//We create the config.json file
$configuration = '{';
$configuration .= '"website_name":"' . $_SESSION['app_name'] . '",';
$configuration .= '"website_url":"' . $_SESSION['app_url']  . '",';
$configuration .= '"website_path":"' . $_SESSION['app_path']  . '",';
$configuration .= '"website_email":"' . $_SESSION['account_email']  . '",';
$configuration .= '"website_lang":"' . $_SESSION['app_language']  . '",';
$configuration .= '"website_frontTheme":"default",';
$configuration .= '"website_backTheme":"default",';
$configuration .= '"website_timezone":"' . $_SESSION['app_timezone']  . '",';
$configuration .= '"website_maintenance":"0"';
$configuration .= '}';
file_put_contents('../config/config.json', $configuration);

//We create the db.json file
$db = '{';
$db .= '"host":"' . $_SESSION['db_host'] . '",';
$db .= '"user":"' . $_SESSION['db_user'] . '",';
$db .= '"password":"' . $_SESSION['db_password'] . '",';
$db .= '"dbname":"' . $_SESSION['db_name'] . '",';
$db .= '"prefix":"' . $_SESSION['db_prefix'] . '"';
$db .= '}';
file_put_contents('../config/db.json', $db);

//We add the prefix to the tables in the SQL file
$search = array("TABLES `", "TABLE `", "EXISTS `", "INTO `", "REFERENCES `");
$replace = array("TABLES `" . $_SESSION['db_prefix'] . "", "TABLE `" . $_SESSION['db_prefix'] . "", "EXISTS `" . $_SESSION['db_prefix'] . "", "INTO `" . $_SESSION['db_prefix'] . "", "REFERENCES `" . $_SESSION['db_prefix'] . "");

$fileContent = file_get_contents("expressif_" . $_SESSION['app_language'] . ".sql");
$fileContent = str_ireplace($search, $replace, $fileContent);

file_put_contents("expressif_" . $_SESSION['app_language'] . ".sql", $fileContent);

//We execute the SQL file
$db = new PDO('mysql:host=' . $_SESSION['db_host'] . ';dbname=' . $_SESSION['db_name'] . ';charset=utf8', $_SESSION['db_user'], $_SESSION['db_password']);
$fileContent = file_get_contents("expressif_" . $_SESSION['app_language'] . ".sql");
$query = $db->prepare($fileContent);
$query->execute();

//We create the administrator account
$password = password_hash($_SESSION['account_password'], PASSWORD_DEFAULT);
$query = $db->prepare('INSERT INTO ' . $_SESSION['db_prefix'] . 'members_members(username, password, email, level) VALUES(:username, :password, :email, 3)');
$query->bindValue(':username', $_SESSION['account_username'], PDO::PARAM_STR);
$query->bindValue(':password', $password, PDO::PARAM_STR);
$query->bindValue(':email', $_SESSION['account_email'], PDO::PARAM_STR);
$query->execute();
		
//Installation is done - We delete install folder
delete_files("../install");
function delete_files($dir) { 
	foreach(glob($dir . '/*') as $file) { 
		if(is_dir($file)) 
			delete_files($file); 
		else 
			unlink($file); 
  } 
  rmdir($dir); 
}

//We redirect the user to the software
header("location: " . $_SESSION['app_url'] . $_SESSION['app_path']);
exit();

?>