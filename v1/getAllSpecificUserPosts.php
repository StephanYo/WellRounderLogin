<?php


if($_SERVER['REQUEST_METHOD'] == 'GET'){

//require_once('Constants.php');
require_once '../includes/Constants.php';

$result2 = array();



$username = $_GET['username'];
//$id = $_GET['id']


$sql = "SELECT * FROM newsfeed WHERE username='" .$username. "'";
//$sql = "SELECT * FROM users WHERE id='" .$id. "'";


$r = mysqli_query($con, $sql);

$res = mysqli_fetch_array($r);

$results = array();

array_push($results, array(
	"id"=>$res['id'],
	"post"=> $res['post'],
	"username" => $res['username'],
	"timeofpost" =>$res['timeofpost'],
	"dateofpost"=>$res['dateofpost']

	)
	);

echo json_encode(array("results" => $results));

mysqli_close($con);

//$sql = "SELECT `id`, `username`, `email`, `phonenumber`, `birthdate`, `lastname`, `firstname`, `middlename` FROM `users` WHERE `username=`" .$username. "'"	
	

}