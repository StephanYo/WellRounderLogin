<?php

require_once '../includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'GET'){

	if(isset($_GET['username'])){

		$db = new DbOperations();

		$result = $db->getTheSpecificUser($_GET['username'])

		

	}

}