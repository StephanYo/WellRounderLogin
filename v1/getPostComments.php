<?php

if($_SERVER['REQUEST_METHOD'] == 'GET'){

require_once '../includes/Constants.php';

//$result2 = array();

$post_id = $_GET['post_id'];

$sql = "SELECT * FROM newsfeedcomments WHERE post_id='" .$post_id. "'";

$r = mysqli_query($con, $sql);

	// while($row = mysqli_fetch_array($r)){
	// 	$id = $row['id'];
	// 	$comment = $row['comment'];
	// 	$username = $row['username'];
	// 	$post_id = $row['post_id'];
	// }


			


$results = array();
while($res = mysqli_fetch_array($r)){
	
	

	array_push($results, array(
		"id"=>$res['id'],
		"comment" => $res['comment'],
		"username"=>$res['username'],
		"post_id" =>$res['post_id']

		)
	);
}
//echo "heyo";
echo json_encode(array( $results));


//echo json_encode($r);

mysqli_close($con);



}