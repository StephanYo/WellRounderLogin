<?php

	//this is for an actual server xDD
	// define('DB_NAME', 'sql12212787' );
	// define('DB_USER', 'sql12212787');
	// define('DB_PASSWORD','htR5zQ2vlu');
	// define('DB_HOST','sql12.freesqldatabase.com');
	
	//use this for localhost php
	define('DB_NAME', 'sql3230579' );
	define('DB_USER', 'sql3230579');
	define('DB_PASSWORD','BUejyMEk9Z');
	define('DB_HOST','sql3.freesqldatabase.com');


$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Unable to Connect');