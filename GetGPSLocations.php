<?php
require 'GetConnDataFromFile.php';

	header("content-type:application/json");
	$conn_data = GetConnDataFromFile("../connection.txt");
        $conn = new mysqli($conn_data[0], $conn_data[1], $conn_data[2], $conn_data[3]);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}
    
    $data = ["LocationData" => []];
    $data['empty'] = TRUE;
    $sql = $conn->prepare(
        "SELECT g.location_id, g.location_name, g.x_coordinate, g.y_coordinate, g.description, a.species
         FROM GPS_Locations as g
         LEFT JOIN Animal_Locations as a
         ON g.location_id = a.location_id"
    );

    $sql->execute();
    $sql->bind_result($location_id, $location_name, $x_coordinate, 
        $y_coordinate, $description, $animal_species);
    
    while($sql->fetch())
    { 
        $location_data = array();
        $location_data['location_id'] = $location_id;
        $location_data['location_name'] = $location_name;
        $location_data['x_coordinate'] = $x_coordinate;
        $location_data['y_coordinate'] = $y_coordinate;
        $location_data['description'] = $description;
        $location_data['species'] = $animal_species;
        $data["LocationData"][] = $location_data;
        $data['empty'] = FALSE;
    } 

	echo json_encode($data);

	$conn->close();
?>
