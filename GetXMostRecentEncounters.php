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
    $encounter_limit = $_GET["encounter_limit"];

    $data = ["JournalEntryData" => []];
    $data['empty'] = TRUE;

    #comma is an inner join, it is part of the FROM statement
    $sql = $conn->prepare(
        "SELECT a.encounter_id, a.species, a.encounter_type, 
            a.health_1, a.health_2, a.health_3, a.encounter_date, 
            c.encounter_date as caught_date, c.health_1 as c_health_1, 
            c.health_2 as c_health_2, c.health_3 as c_health_3
        FROM Animal_Encounter as a 
        LEFT JOIN Animal_Encounter as c 
            on a.encounter_id = c.encounter_id and a.username = c.username 
            and a.encounter_type = 'released' and c.encounter_type = 'caught', 
        (SELECT encounter_id, max(encounter_date) as max_date 
         FROM Animal_Encounter 
         WHERE username=?
            GROUP BY encounter_id) as b
         WHERE a.username=? and a.encounter_id = b.encounter_id and a.encounter_date = b.max_date
         ORDER BY a.encounter_date desc
         LIMIT ?"  
    );

    $sql->bind_param("ssi", $username, $username, $encounter_limit);
    $sql->execute();
    $sql->bind_result($encounter_id_1, $animal_species, $encounter_type_1, 
        $h1, $h2, $h3, $date, $caught_date, $c_h1, $c_h2, $c_h3);
    
    while($sql->fetch())
    { 
        $journal_entry = array();
        $journal_entry['animal_id'] = $encounter_id_1;
        $journal_entry['species'] = $animal_species;
        $journal_entry['encounter_type'] = $encounter_type_1;
        $journal_entry['health_1'] = $h1;
        $journal_entry['health_2'] = $h2;
        $journal_entry['health_3'] = $h3;
        $journal_entry['encounter_date'] = $date;
        $journal_entry['caught_date'] = $caught_date;
        $journal_entry['caught_health_1'] = $c_h1;
        $journal_entry['caught_health_2'] = $c_h2;
        $journal_entry['caught_health_3'] = $c_h3;
        $data["JournalEntryData"][] = $journal_entry;
        $data['empty'] = FALSE;
    }

	echo json_encode($data);
	$conn->close();
?>
        