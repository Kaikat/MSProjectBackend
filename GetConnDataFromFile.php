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
?>

