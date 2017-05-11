<?php

function GetConnDataFromFile($filename)
{
	$dbFile = fopen($filename, "r") or die("Unable to open file!");
	$conn_data = array();
	if ($dbFile)
	{
		$i = 0;
		while(($line = fgets($dbFile)) !== false)
		{
			if (preg_match('/"([^"]+)"/', $line, $m)) 
			{
    			$conn_data[$i] =  $m[1];
				$i = $i + 1;
			} 
			else 
			{
   				//preg_match returns the number of matches found, 
   				//so if here didn't match pattern
			}
		}
	}

	fclose($dbFile);
	return $conn_data;
}

	header("content-type:application/json");
    $conn_data = GetConnDataFromFile("../connection.txt");
    $conn = new mysqli($conn_data[0], $conn_data[1], $conn_data[2], $conn_data[3]);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

    $data = array();
$data['id'] = "caught";
$data['error'] = TRUE;

$username = $_GET["username"];
$animal_species = $_GET["animal_species"];
$nickname = $_GET["nickname"];
$encounter_id = $_GET["encounter_id"];
$health_1 = $_GET["health1"];
$health_2 = $_GET["health2"];
$health_3 = $_GET["health3"];
$size = $_GET["size"];
$age = $_GET["age"];
$weight = $_GET["weight"];

$sql = "INSERT INTO Owned_Animals VALUES(
'$username', '$encounter_id', '$animal_species', '$nickname', '$size', '$age', '$weight', '$health_1', '$health_2', '$health_3', FALSE
)";


$conn->query($sql);

$caught = "caught";

$sql_encounter = "INSERT INTO Animal_Encounter VALUES('$username', '$encounter_id', 
'$animal_species', '$caught', '$health_1', '$health_2', '$health_3', NOW())";

$conn->query($sql_encounter);

$data['error'] = FALSE;
	echo json_encode($data);
	$conn->close();

?>

