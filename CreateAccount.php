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
    $name = $_GET["name"];
    $password = $_GET["password"];
    $email = $_GET["email"];
    $currency = 1000;
    $avatar = "lena";

    $cost = 10;
    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    $salt = sprintf("$2a$%02d$", $cost) . $salt;
    $hash = crypt($password, $salt);

    $data = array();
    $data["id"] = "account_creation";
    $data["error"] = TRUE;

    $sql_emailExists = "SELECT * from User WHERE email = '$email'";
    $email_valid = TRUE; 
    $email_result = $conn->query($sql_emailExists);
    if ($email_result->num_rows != 0)
    {
	$email_valid = FALSE;
	$data['message'] = "An account with that email already exists!";
    }


    $sql_userExists = "SELECT username from User
                       WHERE username = '$username'";
    $result = $conn->query($sql_userExists);
    if ($result->num_rows == 0 && $email_valid == TRUE)
    {
        $sql_create_account = "INSERT INTO User values('$username', '$name', '$hash', '$salt', '$email', '$currency', '$avatar')";
        $conn->query($sql_create_account);
        $data["error"] = FALSE;
        $data["message"] = "Account Created";
    }
    else if ($result->num_rows != 0)
    {
	$data["message"] = "A player with that username already exists. Please select a different username.";
    }

    echo json_encode($data);
    $conn->close();
?>

