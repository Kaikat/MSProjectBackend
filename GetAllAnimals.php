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

    $data = ["AnimalData" => []];

	$sql = "SELECT * from animal";
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
		    $animal = array();
		    $animal['species'] = $row['species'];
			$animal['name'] = $row['animal_name'];
		    $animal['description'] = $row['description'];	
            $animal['habitat_level'] = $row['habitat_level'];
            $animal['min_size'] = $row['min_size'];
            $animal['max_size'] = $row['max_size'];
            $animal['min_age'] = $row['min_age'];
            $animal['max_age'] = $row['max_age'];
            $animal['min_weight'] = $row['min_weight'];
		    $animal['max_weight'] = $row['max_weight'];
		    $animal['colorkey_map_file'] = $row['colorkey_map_file'];
			
		    $data["AnimalData"][] = $animal;
		}

		echo json_encode($data);
	}

	$conn->close();

?>


