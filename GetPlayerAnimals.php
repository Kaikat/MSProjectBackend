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

    $data = ["OwnedAnimalData" => []];
    $data['empty'] = TRUE;

	$username = $_GET['username'];
	$released = $_GET['released'];
	$sql = "SELECT * from Owned_Animals WHERE username = '$username' AND released = '$released'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		$data['empty'] = FALSE;
		while ($row = $result->fetch_assoc())
		{
		    $animal = array();
	  	    $animal['animal_id'] = $row['encounter_id'];
		    $animal['animal_species'] = $row['species'];
		    $animal['nickname'] = $row['nickname'];			
            $animal['health_1'] = $row['health_1'];
		    $animal['health_2'] = $row['health_2'];
		    $animal['health_3'] = $row['health_3'];
            $animal['size'] = $row['size'];
            $animal['age'] = $row['age'];
		    $animal['weight'] = $row['weight'];
		    $data["OwnedAnimalData"][] = $animal;
		}
	}

	else
	{
		$animal = array();
        $animal['animal_id'] = "0";
        $animal['nickname'] = "0";
        $animal['health'] = 0;
        $animal['size'] = 0;
        $animal['age'] = 0;
        $animal['color_file'] = "0";
        $data["OwnedAnimalData"][] = $animal;
	}

	echo json_encode($data);

	$conn->close();
?>