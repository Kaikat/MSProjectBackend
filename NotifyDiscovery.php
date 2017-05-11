<?php
require 'GetConnDataFromFile.php';

	header("content-type:application/json");
	$conn_data = GetConnDataFromFile("../connection.txt");
        $conn = new mysqli($conn_data[0], $conn_data[1], $conn_data[2], $conn_data[3]);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

    function GenerateRandomNumberInRange($min_value, $max_value) 
    {
        $rand_number = (rand() + 1) / (getrandmax() - 1);
        $rand_number = $rand_number * ($max_value - $min_value);
        $rand_number = $rand_number + $min_value;
        $rand_number = floor($rand_number * 100) / 100;
        return $rand_number;
    }

    $username = $_GET["username"];
    $animal_species = $_GET["species"];
    
    $data = array();
    $data['id'] = "animal discovery";
    $data['error'] = TRUE;

    //First Encounter
    $sql_discovered = $conn->prepare(
        "SELECT COUNT(*) FROM Discovered_Animals
         WHERE username=? AND species=?");
    $sql_discovered->bind_param("ss", $username, $animal_species);
    $sql_discovered->execute();
    $sql_discovered->bind_result($discovered_count);
    $sql_discovered->fetch();
    $sql_discovered->close();

    $discovered_time = "";
    if ($discovered_count == 0)
    {
        $discovered = "discovered";
        $sql_add_encounter = $conn->prepare(
            "INSERT INTO Discovered_Animals VALUES(?, ?, NOW())"
        );
        $sql_add_encounter->bind_param("ss", $username, $animal_species);
        $sql_add_encounter->execute();
        $sql_add_encounter->close();

        //Get the timestamp
        $sql_time = $conn->prepare(
            "SELECT encounter_date FROM Discovered_Animals
             WHERE username=? and species=?"
        );
        $sql_time->bind_param("ss", $username, $animal_species);
        $sql_time->execute();
        $sql_time->bind_result($discovered_time);
        $sql_time->fetch();
        $sql_time->close();
    }

    $data['message'] = $discovered_time;

	echo json_encode($data);

	$conn->close();
?>

