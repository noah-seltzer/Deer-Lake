<?php
	$db_array = parse_ini_file($_SERVER["DOCUMENT_ROOT"]."/cfg/db.ini");
	$conn = mysqli_connect($db_array['dbhost'], $db_array['dbuser'], $db_array['dbpass'], $db_array['dbname']);
	$username = mysqli_real_escape_string($conn, $_POST['Username']);
	$password = mysqli_real_escape_string($conn, $_POST['Password']);
	$displayname = mysqli_real_escape_string($conn, $_POST['DisplayName']);
	$passwordconfirm = mysqli_real_escape_string($conn, $_POST['PasswordConfirm']);
	$query = "SELECT * FROM accounts WHERE username='$username'";
	$result = mysqli_query($conn, $query);
	$data = array();
	
	//Checks if email is taken
	if(mysqli_num_rows($result) > 0) {
		$data['taken'] = true;
	} else {
		$data['taken'] = false;
	}
	
	//Checks if passwords are the same
	if($password == $passwordconfirm) {
		$data['passwordvalid'] = true;
		$hashedpass = password_hash($password, PASSWORD_DEFAULT);
	} else {
		$data['passwordvalid'] = false;
	}

	//Checks if email address is correct format
	if(preg_match('/.+@.+/', $username)) {
		$data['emailvalid'] = true;
	} else {
		$data['emailvalid'] = false;
	}
	
	//Inserts data into database if no errors
	if($data['taken'] == false && $data['passwordvalid'] == true && $data['emailvalid'] == true) {
		$query = "INSERT INTO accounts (`username`, `password`, `displayname`) VALUES ('$username', '$hashedpass', '$displayname');";
		mysqli_query($conn, $query);
	}
	
	echo json_encode($data);
?>