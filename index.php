<?php
session_start();
require_once("vendor/autoload.php");

use config\Router;
use config\Config;
use config\Exception;
use vendor\Spyc;

//We check if application is installed
if(file_exists("install")) {
	header('location: install/index.php');
	exit();
}

$router = new Router();
$router->setBasePath(Config::getPath());

//If the user is connected we save the time of his last query
if(isset($_SESSION['idUser'])) {
	$db = Config::getDBInfos();
	$tablePrefix = Config::getDBTablesPrefix();

	$timestamp = time();

	$query = $db->prepare('UPDATE ' . $tablePrefix . 'members_members SET lastQuery = :timestamp WHERE id = :idUser');
	$query->bindValue('idUser', $_SESSION['idUser'], PDO::PARAM_STR);
	$query->bindValue('timestamp', $timestamp, PDO::PARAM_STR);
	$query->execute();
}

//We collect the routes of each module from the frontend interface
$modules = scandir("app/modules");

for($i = 2; $i < count($modules); $i++) {
	if(is_dir("app/modules/".$modules[$i]) && file_exists('app/modules/'.$modules[$i].'/routes.yaml')) {
		$yaml_file = 'app/modules/'.$modules[$i].'/routes.yaml';
		$routes = Spyc::YAMLLoad(file_get_contents($yaml_file));

		foreach($routes as $route_name => $params) {
			$router->map($params[0], $params[1], $params[2], $route_name);
		} 
	}
}

//We collect the routes of the backend interface
$yaml_file = 'app/backend/routes.yaml';
$routes = Spyc::YAMLLoad(file_get_contents($yaml_file));
foreach($routes as $route_name => $params) {
    $router->map($params[0], $params[1], $params[2], $route_name);
}

$match = $router->match();

//We check whether the route exists or not
if ($match === false) 
	throw new Exception("accessError_404");

//We get the controller and the method called
list( $controller, $method ) = explode( '#', $match['target'] );

//If controller->action can be called, we send the parameters
if(is_callable(array($controller, $method))) {
	$obj = new $controller();
	call_user_func_array(array($obj, $method), array($match['params']));

//Else, we redirect the user to a 404 error page
} else
	throw new Exception("accessError_404");