	<?php 

		//getting the dboperation class
	require_once '../includes/DbOperations.php';

		//function validating all the paramters are available
		//we will pass the required parameters to this function 
	function isTheseParametersAvailable($params){
			//assuming all parameters are available 
		$available = true; 
		$missingparams = ""; 
		
		foreach($params as $param){
			if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
				$available = false; 
				$missingparams = $missingparams . ", " . $param; 
			}
		}
		
			//if parameters are missing 
		if(!$available){
			$response = array(); 
			$response['error'] = true; 
			$response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
			
				//displaying error
			echo json_encode($response);
			
				//stopping further execution
			die();
		}
	}
	
		//an array to display response
	$response = array();
	
		//if it is an api call 
		//that means a get parameter named api call is set in the URL 
		//and with this parameter we are concluding that it is an api call
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			
				//the READ operation
					//if the call is getheroes
			case 'getusers':
			$db = new DbOperations();
			$response['error'] = false; 
			$response['message'] = 'Request successfully completed';
			$response['users'] = $db->getUser();
			break; 

			case 'getposts':
			$db = new DbOperations();
			$response['error'] = false;
			$reponse['message'] = 'Request successfully completed';
			$response['post'] = $db->getPost();
			break;

			case 'getChatRooms':
			$db = new DbOperations();
			$response['error'] = false;
			$response['message'] = 'Request successfully completed';
			$response['chat_ids'] = $db->getChatRooms();
			break;

			case'getspecificuser':
			$db = new DbOperations();
			$response['error'] = false;
			$response['message'] = "Request all good cuz";
			$response['user'] = $db->getSpecficUser($_POST['username']);
			break;



			

			

				//the UPDATE operation
			case 'updateuser':
					//isTheseParametersAvailable(array('id','username', 'phonenumber'));
			echo "1";
			$db = new DbOperations();
			$result = $db->updateUser(
				$_POST['id'],
				$_POST['username'],
				$_POST['email'],
				$_POST['phonenumber'],
				$_POST['lastname'],
				$_POST['firstname'],
				$_POST['middlename']
				);
			
			if($result == 1){
				echo "2";
				$response['error'] = false; 
				$response['message'] = 'User updated successfully';
				$response['user'] = $db->getUser();
			}else{
				echo "3";
				$response['error'] = true; 
				$response['message'] = 'Some error occurred please try again';
			}
			break; 



			case 'getthespecificuser':

			if(isset($_GET['username'])){
				echo "you are past the frist if statement";
				$db = new DbOperations();
				
				$response = $db->getTheSpecificUser($_GET['username']);

				if($db->getTheSpecificUser($_GET['username'])){
					echo "you are in the second if statement";
					$response['error'] = false; 
					$response['user'] = getTheSpecificUser('username');
							//$response['id'] = $user['id'];
							//$response['username'] = $user['username'];
						//	$response['email'] = $user['email'];
						//	$response['phonenumber'] = $user['phonenumber'];
						//	$response['lastname'] = $user['lastname'];
						//	$response['firstname'] = $user['firstname'];
						//	$response['middlename'] = $user['middlename'];



				}else{
					echo "-->";
					var_dump($_GET['username']);
					die();
					$response['error'] = true;
					$response['message'] = "something went wrong";

				}


			}else{
				$response['error'] = true;
				$response['message'] = "please enter a valid username";

			}
			break;

			case 'deleteUsersPosts':

			if(isset($_GET['username'])){
				$db = new DbOperations();

				if($db->deleteUserPosts($_GET['username'])){
					$response['error'] = false; 
					$response['message'] = 'Users posts have been deleted';
					$response['posts'] = $db->getPost();
					
				}else{
					$response['error'] = true; 
					$response['message'] = 'Some error occurred please try again';
				}
			}else{
				$response['error'] = true; 
				$response['message'] = 'Nothing to delete, provide an id please';
			}
			break;

			case 'getUsernameWithId':

			if(isset($_GET['id'])){
				$db = new DbOperations();
				$response['error'] = false;
				$response['message'] = 'Username is:';
				$response['username'] = $db->selectUsername($_GET['id']);


			}
			break;
			
			case 'getAllSpecificMessages':

			if(isset($_GET['chat_id'])){
				$db = new DbOperations();
				$response['error'] = false;
				$response['message'] = 'chat_id|' . $_GET['chat_id'];
				$response['messages'] = $db->getAllSpecificMessages($_GET['chat_id']);

			}

			break;

			case 'getAllSpecificPosts':

			if(isset($_GET['username'])){
				$db = new DbOperations();
				$response['error'] = false;
				$response['message'] = 'username|' . $_GET['username'];
				$response['posts'] = $db->getAllSpecificPosts($_GET['username']);

			}

			break;

			case 'postComment':
				//this is working bruh.

			if(isset($_GET['post_id'])){

					//echo "1";

					//if(isset($_POST['comment'])and isset($_POST['usernane'])){
						//echo "2";
				$post_id = $_GET['post_id'];

				$db = new DbOperations();

				$result = $db->createComment(
					$_POST['comment'],
					$_POST['username'],
					$_POST['post_id']
					);

				if($result == 1){
						//echo "3";
					$response['error'] =false;
					$response['message'] = 'Comment successfully posted';

				}elseif($result==2){
						//echo "4";
					$response['error'] = true;
					$response['message'] = "Some sort of error occured";

				}elseif($result == 0){
						//echo "5";
					$response['error'] = true;
					$response['message'] = "Please do not leave the comment blank";
				}
				else{
						//echo "6";
					$response['error'] = true;
					$response['message'] = "Required fields are missing.";
				}

				//}
			}		
			
				//echo "7";

			break;
			
			

				//the delete operation
			case 'deleteuser':

					//for the delete operation we are getting a GET parameter from the url having the id of the record to be deleted
			if(isset($_GET['id'])){
				
				$db = new DbOperations();
				$username = $db->selectUsername($_GET['id']);
				
				if($db->deleteUser($_GET['id'])){

					$db->deleteUserPosts($username);
					$response['error'] = false; 
					$response['message'] = 'User deleted successfully';
					$response['users'] = $db->getUser();
				}else{
					$response['error'] = true; 
					$response['message'] = 'Some error occurred please try again';
				}
			}else{
				$response['error'] = true; 
				$response['message'] = 'Nothing to delete, provide an id please';
			}
			break; 
		}
		
	}else{
			//if it is not api call 
			//pushing appropriate values to response array 
		$response['error'] = true; 
		$response['message'] = 'Invalid API Call';
	}
	
		//displaying the response in json structure 
	echo json_encode($response);