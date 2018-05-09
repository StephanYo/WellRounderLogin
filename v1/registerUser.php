<?php


require_once '../includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	if(
		isset($_POST['username'])and 
		isset($_POST['password'])and
		isset($_POST['email'])and
		isset($_POST['phonenumber'])and
		isset($_POST['birthdate'])and
		isset($_POST['lastname'])and
		isset($_POST['firstname'])and
		isset($_POST['middlename']))
	{
		//operate the data further

		$db = new DbOperations();

		$result = $db->createUser(
			$_POST['username'],
			$_POST['password'],
			$_POST['email'],
			$_POST['phonenumber'],
			$_POST['birthdate'],
			$_POST['lastname'],
			$_POST['firstname'],
			$_POST['middlename']
			);

		
		if($result == 1){	
			$response['error'] = false;
			$response['message'] = "User registered successfully";

		}elseif($result == 2){
			$response['error'] = true;
			$response['message'] = "Some sort of error occured";

		}elseif($result == 0){
			$response['error'] = true;
			$response['message'] = "Username or Email already exists";
		}
	}else{
	
		$response['error'] = true;
			$response['message'] = "Required fileds are missing";
	}

}else{
	
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}			



echo json_encode($response);
