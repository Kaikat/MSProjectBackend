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
	$data = ["ResultingData" => []];	
	#$data = array();

	$conn_data = GetConnDataFromFile("../connection.txt");

	#$data["ResultingData"][] = $_GET["tag"];
	#$conn = new mysqli("localhost:8889", "root", "root", "testDatabase");
    $conn = new mysqli($conn_data[0], $conn_data[1], $conn_data[2], $conn_data[3]);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT username from user";
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		$k = 0;
		while ($row = $result->fetch_assoc())
		{
			#echo "<p>" . $row['column1'] . "</p>";
			#array_push($data["ResultingData"], $row['username']);
			$data["ResultingData"][] = $row['username'];
		}

		echo json_encode($data);
	}

	$conn->close();

?>

