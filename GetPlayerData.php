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
    
    $data = array();
    $sql = "SELECT * from User WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $data['name'] = $row['name'];
            $data['avatar'] = $row['avatar'];
            $data['currency'] = $row['currency'];
        }
    }    

	echo json_encode($data);

	$conn->close();
?>

