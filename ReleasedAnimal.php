<?php
require 'GetConnDataFromFile.php';

	header("content-type:application/json");
	$conn_data = GetConnDataFromFile("../connection.txt");
        $conn = new mysqli($conn_data[0], $conn_data[1], $conn_data[2], $conn_data[3]);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$data = array();
    $data['id'] = "released";

    $username = $_GET["username"];
    $encounter_id = $_GET["encounter_id"];
    $animal_species = $_GET["animal_species"];
    $health_1 = $_GET["health1"];
    $health_2 = $_GET["health2"];
    $health_3 = $_GET["health3"];

    //Update the Owned Animals table
    $sql = $conn->prepare("UPDATE Owned_Animals SET released = 1 
                           WHERE username=? AND encounter_id=?");
    $sql->bind_param("si", $username, $encounter_id);
    $sql->execute();
    $sql->close();
 
    //Add the release encounter to the Animal_Encounter table
    $released = "released";
    $sql_insert_encounter = $conn->prepare(
        "INSERT INTO Animal_Encounter VALUES(?, ?, ?, ?, ?, ?, ?, NOW())"
    );
    $sql_insert_encounter->bind_param("sissddd", $username, $encounter_id, 
        $animal_species, $released, $health_1, $health_2, $health_3);
    $sql_insert_encounter->execute();
    $sql_insert_encounter->close();

    $data['error'] = FALSE;
	echo json_encode($data);
	$conn->close();
?>

