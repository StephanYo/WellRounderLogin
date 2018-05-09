<?php

require_once '../includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	if(
		isset($_POST['send_username'])and 
		isset($_POST['rec_username'])and
		isset($_POST['message'])and
		isset($_POST['time_stamp'])
		)
	{
		//operate the data further

		$db = new DbOperations();

		
		echo "poop";

		if($db->getUserIdbyUsername($_POST['rec_username']) == null){

		
			$response['error'] = true;
			$response['message'] = "Username is in correct";
			echo json_encode($response);
			
			
		
		}else{

		$reciver_id = implode("|", $db->getUserIdbyUsername($_POST['rec_username']));
		}

		$sender_id = implode("|", $db->getUserIdbyUsername($_POST['send_username']));

		//$rec_username = implode("|", $db->getUserById($_POST['reciver_id']));
		//$send_username = implode("|", $db->getUserById($_POST['sender_id'])	);

		if($sender_id> $reciver_id){

			$chat_id =  $reciver_id . ':' . $sender_id;

		}
		else{

			$chat_id = $sender_id. ':' . $reciver_id;

		}
		$messages_amt = $db->howManyMessagesInChatRoom($chat_id);
		var_dump($messages_amt);
		
		if($messages_amt == 0){
			echo " messages should be 1 ";
			$messages_amt = 1;
		}else{
			echo " test 1 ";
			$messages_amt = implode("|", $db->howManyMessagesInChatRoom($chat_id));
			++$messages_amt;
			
		}
		

		echo $messages_amt;
		
		//echo $send_username;
	//		var_dump($send_username);


		$result = $db->sendMessage(
			$sender_id,
			$reciver_id,
			$_POST['rec_username'],
			$_POST['send_username'],
			$_POST['message'],
			$messages_amt,
			$_POST['time_stamp']
			);

		
		if($result == 1){	
			$response['error'] = false;
			$response['message'] = "Your message has been sent";

		}elseif($result == 2){
			$response['error'] = true;
			$response['message'] = "Some sort of error occured";

		}elseif($result == 0){
			$response['error'] = true;
			$response['message'] = "Please do not leave the message empty";
		}elseif($result == 3){
			$response['error'] == true;
			$response['message'] = "Something went wrong try again later";
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