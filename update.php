<?php
session_start();

require_once("vendor/autoload.php");

use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;


//We check if the user has the rights to update the software
if(isset($_SESSION['idUser'])) {
	$membersManager = new MembersManager();
	$member = new Member();
	$member->setId($_SESSION['idUser']);
	$member = $membersManager->get($member);
	
	if($member->getLevel() != 3) {
		header('location: index.php');
		exit();
	}
} else {
	header('location: index.php');
	exit();
}

require_once('vendor/update/update.php');