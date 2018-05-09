<?php

require_once '../includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	//echo "You have made into first if ";


	if(
		isset($_POST['id'])and 
		isset($_POST['username'])and
		isset($_POST['email'])and
		isset($_POST['phonenumber'])and
		isset($_POST['lastname'])and
		isset($_POST['firstname'])and
		isset($_POST['middlename'])and
		isset($_POST['id']))
	{

//		echo "You are past the isset part";

		//we can opperate the data further

		$db = new DbOperations();
		
		$result = $db->updateUser(
		
			$_POST['username'],
			$_POST['email'],
			$_POST['phonenumber'],
			$_POST['lastname'],
			$_POST['firstname'],
			$_POST['middlename'],
			$_POST['id']


			);

		if($result == 0){
			$response['error'] = true;
			$response['message'] = 'Username or Email is already in use';

		}elseif($result == 1){
			$response['error'] = false; 
			$response['message'] = 'User succesfully updated';
			$response['user'] = $db->getUser();
		}elseif($result == 2){
			$response['error'] = false;
			$response['message'] = 'Some error occured, please try again';
		}

	
	
	}else{
		$response['error'] = true;
		$response['message'] = "Required fileds are missing";
	}
}else{
	$response['error'] = true;
	$response['message'] - 'Invalid Request';
}

echo json_encode($response);