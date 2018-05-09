<?php

require_once '../includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	if(isset($_GET['post_id'])){

				//echo "1";

				if(isset($_POST['comment'])and isset($_POST['usernane'])){
					//echo "2";
					$post_id = $_GET['post_id'];

				$db = new DbOperations();

				$result = $db->createComment(
					$_POST['comment'],
					$_POST['username'],
					$post_id
					);

				if($result == 1){
					//echo "3";
					$response['error'] =false;
					$response['message'] = 'Your comment successfully posted';

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

			}
		}		
		
		//echo "7";
}

echo json_encode($response);