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
    $discovered = $_GET["encounter_type"];
    $data = ["EncounterData" => []];
    $data['empty'] = TRUE;

    $sql = $conn->prepare("SELECT * FROM Animal_Encounter
                           WHERE username=? AND encounter_type=?");
    $sql->bind_param("ss", $username, $discovered);
    $sql->execute();
    $sql->bind_result($u, $encounter_id, $species, $t, $h1, $h2, $h3, $date);
    
    while($sql->fetch())
    { 
        $encountered_animal = array();
        $encountered_animal['animal_id'] = $encounter_id;
        $encountered_animal['species'] = $species;
        $encountered_animal['health_1'] = $h1;
        $encountered_animal['health_2'] = $h2;
        $encountered_animal['health_3'] = $h3;
        $encountered_animal['encounter_date'] = $date;
        $data["EncounterData"][] = $encountered_animal;
        $data['empty'] = FALSE;
    }

	echo json_encode($data);

	$conn->close();
?>

