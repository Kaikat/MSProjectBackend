<?php
require 'GetConnDataFromFile.php';

	header("content-type:application/json");
	$conn_data = GetConnDataFromFile("../connection.txt");
    $conn = new mysqli($conn_data[0], $conn_data[1], $conn_data[2], $conn_data[3]);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$username = $_GET["username"];
    $sql_userExists = "SELECT username from user
                       WHERE username = '$username'";
    $result = $conn->query($sql_userExists);
    
    $data = array();
    $data['id'] = "session_id";
    $data['error'] = TRUE;

    if ($result->num_rows > 0)
    {
        $password = $_GET["password"];
        $sql_get_salt = "SELECT salty from User
                         WHERE username = '$username'";
        $salt = $conn->query($sql_get_salt);

        $read_s = $salt->fetch_assoc()['salty'];
        $hash = crypt($password, $read_s);

        $sql = "SELECT username from user 
                WHERE username = '$username' and pass_hash = '$hash'";
    
        $result = $conn->query($sql);

        
	    if ($result->num_rows > 0)
	    {
            // check the session table
            $sql_check_session = "SELECT username from Session
                                  WHERE username = '$username'";
            $result_session_check = $conn->query($sql_check_session);
            if ($result_session_check->num_rows > 0)
            {
                $sql_delete = "DELETE FROM Session
                               WHERE username = '$username'";
                $conn->query($sql_delete);
            }

            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $sql_insert = "INSERT INTO Session values('$username', '$token')";
            $conn->query($sql_insert);
            $data['message'] = $token;
            $data['error'] = FALSE;
	    }
    }

	echo json_encode($data);

	$conn->close();
?>

