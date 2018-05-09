<?php


if($_SERVER['REQUEST_METHOD'] == 'GET'){

//require_once('Constants.php');
require_once '../includes/Constants.php';

$result2 = array();



$chat_id = $_GET['chat_id'];
//$id = $_GET['id']


$sql = "SELECT * FROM messages WHERE chat_id ='" .$chat_id. "'";
//$sql = "SELECT * FROM users WHERE id='" .$id. "'";


$r = mysqli_query($con, $sql);

$res = mysqli_fetch_array($r);

$results = array();

array_push($results, array(
	"id"=>$res['id'],
	"chat_id" =>$res['chat_id'],
	"sender_id"=>$res['sender_id'],
	//"password" => $res['password'],
	"reciver_id"=> $res['reciver_id'],
	"message" => $res['message'],
	//"birthdate" => $res['brithdate'],
	"time_stamp" => $res['time_stamp']


	)
	);

echo json_encode(array("results" => $results));

mysqli_close($con);

//$sql = "SELECT `id`, `username`, `email`, `phonenumber`, `birthdate`, `lastname`, `firstname`, `middlename` FROM `users` WHERE `username=`" .$username. "'"	
	

}