<?php

	require_once 'Constants.php';
	class DbOperations{
		private $con;

		//poop 
		function __construct(){
			require_once dirname(__FILE__).'/DbConnect.php';

			$db = new DbConnect();

			//$con = $db->connect();

			$this->con = $db->connect();
		}

		public function createUser($username, $pass, $email, $phonenumber, $birthdate, $lastname, $firstname, $middlename){

			if($this->isUserExists($username, $email)){
				return 0;
			}else{
				$password = md5($pass);
				$stmt = $this->con->prepare("INSERT INTO `users` (`id`, `username`, `password`, `email`, `phonenumber`, `birthdate`, `lastname`, `firstname`, `middlename`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ? );");

				$stmt->bind_param("sssissss", $username, $password, $email, $phonenumber, $birthdate, $lastname, $firstname, $middlename);



				if($stmt->execute()){
					return 1;
				}else{
					return 2;
				}
			}

		}

	public function updateUser( $username, $email, $phonenumber, $lastname, $firstname, $middlename, $id){
			
			 $stmt = $this->con->prepare("UPDATE users SET username = ?, email = ?, phonenumber = ?, lastname = ?, firstname =?, middlename = ? WHERE id = ?");
			
			$stmt->bind_param("ssisssi", $username, $email, $phonenumber, $lastname, $firstname, $middlename, $id);
			
			if($stmt->execute()){
					return 1;
				}else{
					return 2;
				}
			}

		  public function userLogin($username, $pass){
            $password = md5($pass);
            $stmt = $this->con->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss",$username,$password);
            $stmt->execute();
            $stmt->store_result(); 
            return $stmt->num_rows > 0; 
        }

	
		public function getUserByUsername($username){
			$stmt = $this->con->prepare("SELECT * FROM users WHERE username = ?");
			$stmt ->bind_param("s",$username);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		public function getUserIdbyUsername($username){
			$stmt = $this->con->prepare("SELECT id FROM users WHERE username = ?");
			$stmt ->bind_param("s",$username);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();

		}

		public function getUserById($id){
			$stmt = $this->con->prepare("SELECT username FROM users where id =?");
			$stmt ->bind_param("i", $id);
			$stmt ->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		public function getUserByEmail($email){
			$stmt = $this->con->prepare("SELECT * FROM users WHERE email = ?");
			$stmt ->bind_param("s",$email);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		public function isUserExists($username, $email){
			 $stmt = $this->con->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute(); 
            $stmt->store_result(); 
            return $stmt->num_rows > 0; 

		}

		public function isChatRoomExists($chat_id){
			$stmt = $this->con->prepare("SELECT chat_id FROM chat_rooms WHERE chat_id = ?");
			$stmt->bind_param("s", $chat_id);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;

		}

		public function howManyMessagesInChatRoom($chat_id){
			$stmt = $this->con->prepare("SELECT messages_amt FROM chat_rooms WHERE chat_id = ? ");
			$stmt->bind_param("s", $chat_id);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();

		}


		public function createPost($post, $username, $dateofpost, $timeofpost){

			if(empty($post)){
				return 0;
			}else{
				$stmt = $this->con->prepare("INSERT INTO `newsfeed` (`id`, `post`, `username`, `timeofpost`, `dateofpost`) VALUES (NULL, ?, ?, ?, ?);");

				$stmt->bind_param("ssss", $post, $username, $dateofpost, $timeofpost);

				if($stmt->execute()){
					return 1;
				}else{
					return 2;
				}

			}
		}

		public function sendMessage( $sender_id, $reciver_id, $rec_username, $send_username, $message, $time_stamp, $messages_amt){
				$msgs = 1;

				



			if(empty($message)){
				return 0;
			}else{
				
				if($sender_id > $reciver_id){

					$chat_id = (string)$reciver_id . ':' . (string)$sender_id;
					
				}
				else{

					$chat_id = (string)$sender_id . ':' . (string)$reciver_id;
					
				}


				//$numberMsgs = howManyMessagesInChatRoom($chat_id);


				//checks if the chat exsists
				if($this->isChatRoomExists($chat_id)){
					
					//it does exsist

					//$numberMsgs = howManyMessagesInChatRoom($chat_id) + 1;

					//$stmt2 = $this->con->prepare("UPDATE `chat_rooms` SET `messages` = `messages + 1`  WHERE `chat_id` = $chat_id");
					//$stmt2 = $this->con->prepare("UPDATE `chat_rooms` SET `messages` =  `messages + 1`  WHERE `chat_id` = $chat_id");
					//$stmt2-> bind_param("s", $chat_id); 	

					echo "it exsists";
					$stmt = $this->con->prepare("INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `reciver_id`, `message`, `time_stamp`) VALUES (NULL,?, ?, ?,?, ?);");

					$stmt->bind_param("sssss", $chat_id,  $sender_id, $reciver_id, $message, $time_stamp);

					

					echo "bind param 2 ";
					$stmt2= $this->con->prepare("UPDATE chat_rooms SET last_message = ?, messages_amt = ?, time_stamp = ? WHERE chat_id = ?");
					$stmt2->bind_param("ssss", $message, $messaqges_amt, $time_stamp, $chat_id );

				}else{

					//it does not
					$messages_amt = 1;
					
					$stmt2 = $this->con->prepare("INSERT INTO `chat_rooms`(`id`, `chat_id`, `id_1`, `id_2`, `rec_username`, `send_username`, `last_message`, `messages_amt`) VALUES (NULL,?,?,?,?,?,?,?);");
					
					$stmt2-> bind_param("ssssssi", $chat_id, $reciver_id, $sender_id, $rec_username, $send_username, $message, $messages_amt);

					$stmt = $this->con->prepare("INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `reciver_id`, `message`, `time_stamp`) VALUES (NULL,?, ?, ?,?, ?);");

					$stmt->bind_param("sssss", $chat_id, $sender_id, $reciver_id, $message, $time_stamp);
					if($stmt->execute() && $stmt2->execute()){
						return 1;
					}

				}

				if($stmt->execute() && $stmt2->execute()){
					return 1;
				}else{
					return 2;
				}
			}
		}

		public function createComment($comment, $username, $post_id){

			//$post_id = selectNewsFeedPost($_GET['id']);

			if(empty($comment)){
				return 0;
			}else{
				$stmt = $this->con->prepare("INSERT INTO `newsfeedcomments` (`id`, `comment`, `username`, `post_id`) VALUES (NULL, ?, ?, ?);");
				$stmt-> bind_param("ssi", $comment, $username, $post_id);

				if($stmt->execute()){
					return 1;
				}else{
					return 2;
				}

			}




		}

		public function getUser(){
			$stmt = $this->con->prepare("SELECT id, username, email, phonenumber, birthdate, lastname, firstname, middlename FROM users");
			$stmt-> execute();
			$stmt->bind_result($id, $username, $email, $phonenumber, $birthdate, $lastname, $firstname, $middlename);

			$results = array();

			while($stmt->fetch()){
				$result = array();
				$result['id'] = $id;
				$result['username'] = $username;
				$result['email'] = $email;
				$result['phonenumber'] = $phonenumber;
				$result['birthdate'] = $birthdate;
				$result['lastname'] = $lastname;
				$result['firstname'] = $firstname;
				$result['middlename'] = $middlename;

				array_push($results, $result);
			}
			return $results; 

		}

	
	
		public function getPost(){
			$stmt = $this->con->prepare("SELECT id, post, username, timeofpost, dateofpost FROM newsfeed");
			$stmt-> execute();
			$stmt->bind_result($id, $post, $username, $timeofpost, $dateofpost);

			$results = array();

			while($stmt->fetch()){
				$result = array();
				$result['id'] = $id;
				$result['post'] = $post;
				$result['username'] = $username;
				$result['timeofpost'] = $timeofpost;
				$result['dateofpost'] = $dateofpost;

				array_push($results, $result);
			}
			return $results; 
			
		}

		public function getChatRooms(){
			$stmt = $this->con->prepare("SELECT id, chat_id, id_1, id_2, rec_username, send_username, last_message, messages_amt, time_stamp FROM chat_rooms");
				$stmt->execute();
				$stmt->bind_result($id, $chat_id, $id_1, $id_2, $rec_username, $send_username, $last_message, $messages_amt, $time_stamp);

				$results = array();

				while($stmt->fetch()){
					$result = array();
					$result['id'] = $id;
					$result['chat_id'] = $chat_id;
					$result['id_1'] = $id_1;
					$result['id_2'] = $id_2;
					$result['rec_username'] = $rec_username;
					$result['send_username'] = $send_username;
					$result['last_message'] = $last_message;
					$result['messages_amt'] = $messages_amt;
					$result['time_stamp'] = $time_stamp;

					array_push($results, $result);

				}
				return $results;

			}
		

		public function getAllSpecificMessages($chat_id){
			$stmt = $this->con->prepare("SELECT id, chat_id, sender_id, reciver_id, message, time_stamp FROM messages WHERE chat_id ='" .$chat_id. "'" );
			$stmt->execute();
			$stmt->bind_result($id, $chat_id, $sender_id, $reciver_id, $message, $time_stamp);

			$results = array();

			while($stmt->fetch()){
				

				$result = array();
				//if($result['chat_id'] == $chat_id){

				$result['id'] = $id;
				$result['chat_id'] = $chat_id;
				$result['sender_id'] = $sender_id;
				$result['reciver_id'] =  $reciver_id;
				$result['message'] = $message;
				$result['time_stamp'] = $time_stamp;

				array_push($results, $result);
			//}

			}
			return $results;
		}

		public function getAllSpecificPosts($username){
			$stmt = $this->con->prepare("SELECT id, post, username, timeofpost, dateofpost FROM newsfeed WHERE username ='" .$username. "'" );
			$stmt->execute();
			$stmt->bind_result($id, $post, $username, $timeofpost, $dateofpost);

			$results = array();

			while($stmt->fetch()){
				

				$result = array();
				//if($result['chat_id'] == $chat_id){

				$result['id'] = $id;
				$result['post'] = $post;
				$result['username'] = $username;
				$result['timeofpost'] =  $timeofpost;
				$result['dateofpost'] = $dateofpost;
				
				array_push($results, $result);
			//}

			}
			return $results;
		}


		public function getTheSpecificUser($username){
			//not work, go to the v1 folder one getSpecificUser.php
			
			//$username = $_POST['username'];
			//$username = $_POST['username'];



			$stmt= $this->con-> prepare("SELECT `id`, `username`, `email`, `phonenumber`, `birthdate`, `lastname`, `firstname`, `middlename` FROM `users` WHERE `username` = $username");
			
			$stmt->bind_param("s",  $username);
			//$stmt->bind_result( $id, $username, $email, $phonenumber, $birthdate, $lastname, $firstname, $middlename);
			$stmt->execute();

			//$stmt->bind_result("isssssss", $id, $username, $email, $phonenumber, $birthdate, $lastname, $firstname, $middlename);
			$results = array();

			//$results = $stmt->fetch();

			while($stmt->fetch()){


				$result = array();
				$result['id'] = $id;
				//$result['username'] = $username;
				$result['email'] = $email;
				$result['phonenumber'] = $phonenumber;
				$result['birthdate'] = $birthdate;
				$result['lastname'] = $lastname;
				$result['firstname'] = $firstname;
				$result['middlename'] = $middlename;

				array_push($results, $result);
			}
			return $results; 


		}

		public function getSpecificUser ($username){

			//not work, go to the v1 folder one getSpecificUser.php

			$stmt= $this->con-> prepare("SELECT `id`, `username`, `email`, `phonenumber`, `birthdate`, `lastname`, `firstname`, `middlename` FROM `users` WHERE `username` = $username");

			$res = mysqli_fetch_array($stmt);
			
			$results = array();

			array_push($results, array(
			"id"=>$res['id'],
			"username"=>$res['username'],
			"email"=> $res["email"],
			"phonenumber" => $res['phonenumber'],
			"birthdate" => $res['brithdate'],
			"lastname" => $res['lastname'],
			"firstname"=>$res['firstname'],
			"middlename" => $res['middlename']

			)
			);

			return (array("result"=>$results));
		}

		

		public function deleteUser($id){
			$stmt = $this->con-> prepare("DELETE FROM users WHERE id =? ");
			$stmt->bind_param("i", $id);
			if($stmt->execute())
				return true;

				return false;
		}

		public function deleteUserPosts($username){
			$stmt = $this->con->prepare("DELETE FROM newsfeed WHERE username = ?");
			$stmt-> bind_param("s", $username);
			if($stmt->execute())
				return true;

				return false; 
			


		}

		public function selectUsername($id){

			$con = mysqli_connect('localhost', 'root', '', 'wellrounderlogin') or die('Unable to Connect');

			//$con = $this->con;
			$sql =  "SELECT * FROM users WHERE id='" .$id. "'";

			//$stmt = $this->con->prepare("SELECT FROM users WHERE id = ?");

			$r = mysqli_query($con, $sql);


			$res = mysqli_fetch_array($r);

			//$res = mysqli_fetch_array($stmt);
	
			$results = array();

			array_push($results, array("username"=>$res['username']));
	
			return $results;
		}

		public function selectNewsFeedPost($id){


			$con = mysqli_connect('localhost', 'root', '', 'wellrounderlogin') or die('Unable to Connect');

			
			$sql =  "SELECT * FROM newsfeed WHERE id='" .$id. "'";

			

			$r = mysqli_query($con, $sql);


			$res = mysqli_fetch_array($r);

			//$res = mysqli_fetch_array($stmt);
	
			$results = array();

			array_push($results, array("id"=>$res['id']));
	
			return $results;

		}

	}
