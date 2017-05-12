<?php
	//Connecting to database using information stored on external file
	$db_array = parse_ini_file($_SERVER["DOCUMENT_ROOT"]."/cfg/db.ini");
	$conn = mysqli_connect($db_array['dbhost'], $db_array['dbuser'], $db_array['dbpass'], $db_array['dbname']);
	
	//Retrieving username and password from ajax's POST method
	$username = mysqli_real_escape_string($conn, $_POST['Username']);
	$password = mysqli_real_escape_string($conn, $_POST['Password']);
	
	//Executing query onto database to see if email is registered
	$query = "SELECT * FROM accounts WHERE username='$username'";
	$result = mysqli_query($conn, $query);
	$data = array();
	
	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		//Retrieving hashed password that is stored on database
		$storedpass = $row['password'];
		$data['hashed'] = $storedpass;
		
		if(password_verify($password, $storedpass)) {
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
	} else { 
		$data['success'] = false;
	}
	
	echo json_encode($data);
?>