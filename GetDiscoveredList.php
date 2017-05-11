<?php
require 'GetConnDataFromFile.php';

	header("content-type:application/json");
	$conn_data = GetConnDataFromFile("../connection.txt");
        $conn = new mysqli($conn_data[0], $conn_data[1], $conn_data[2], $conn_data[3]);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

    $data = ["DiscoveredSpeciesData" => []];
    $data['empty'] = TRUE;

	$username = $_GET['username'];

    $sql = $conn->prepare(
        "SELECT * FROM Discovered_Animals WHERE username=? ORDER BY encounter_date DESC");

    $sql->bind_param("s", $username);
    $sql->execute();
    $species_list = $sql->get_result();

    while ($row = $species_list->fetch_assoc())
    {
	  	$animal['animal_species'] = $row['species'];
        $animal['discovered_date'] = $row['encounter_date'];
		$data["DiscoveredSpeciesData"][] = $animal;
        $data['empty'] = FALSE;
    }

	echo json_encode($data);

	$conn->close();
?>