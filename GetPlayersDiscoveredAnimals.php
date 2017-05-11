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
    $encounter_type = $_GET["encounter_type"];
    $data = array();

    if ($encounter_type == "discovered" || $encounter_type == "Discovered")
    {
        $sql = $conn->prepare("SELECT COUNT(*) FROM Discovered_Animals
                               WHERE username=?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $sql->bind_result($result);
        $sql->fetch();
        $sql->close();
        $data['count'] = $result;
    }
    else
    {
        $sql = $conn->prepare("SELECT COUNT(*) FROM Animal_Encounter
                            WHERE username=? AND encounter_type=?");
        $sql->bind_param("ss", $username, $encounter_type);
        $sql->execute();
        $sql->bind_result($result);
        $sql->fetch();
        $sql->close();
        $data['count'] = $result;
    }

	echo json_encode($data);

	$conn->close();
?>

