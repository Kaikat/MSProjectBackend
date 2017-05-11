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
    $sql_userExists = "SELECT * from Owned_Animals
                       WHERE username = '$username'";
    $result = $conn->query($sql_userExists);

    $data = ["OwnedAnimalData" => []];

    if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			$animal = array();
			$animal['animal_id'] = $row['animal_id'];
			$animal['nickname'] = $row['nickname'];			
            $animal['health'] = $row['health'];
            $animal['size'] = $row['size'];
            $animal['age'] = $row['age'];
            $animal['color'] = $row['color'];
            $data["OwnedAnimalData"][] = $animal;

		}

		echo json_encode($data);
	}

	$conn->close();
?>
