<?php

require_once '../includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	if(
		isset($_POST['post'])and 
		isset($_POST['username'])and
		isset($_POST['timeofpost'])and
		isset($_POST['dateofpost'])
		)
	{
		//operate the data further

		$db = new DbOperations();

		$result = $db->createPost(
			$_POST['post'],
			$_POST['username'],
			$_POST['timeofpost'],
			$_POST['dateofpost']
			);

		
		if($result == 1){	
			$response['error'] = false;
			$response['message'] = "Your post has been created";

		}elseif($result == 2){
			$response['error'] = true;
			$response['message'] = "Some sort of error occured";

		}elseif($result == 0){
			$response['error'] = true;
			$response['message'] = "Please do not leave the post empty";
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