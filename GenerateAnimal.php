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

    //Generate Random Values for the Animal
    $sql = $conn->prepare("SELECT min_size, max_size, 
                           min_age, max_age, 
                           min_weight, max_weight 
                           FROM Animal WHERE species=?");
    $sql->bind_param("s", $animal_species);
    $sql->execute();
    $sql->bind_result($min_size, $max_size, $min_age, $max_age, $min_weight, $max_weight);
    $sql->fetch();
    $sql->close();

    $rand_health1 = rand(1, 100);
    $rand_health2 = rand(1, 100);
    $rand_health3 = rand(1, 100);
    $rand_size = GenerateRandomNumberInRange($min_size, $max_size);
 	$rand_weight = GenerateRandomNumberInRange($min_weight, $max_weight);
    $rand_age = GenerateRandomNumberInRange($min_age, $max_age);
    
    //First Encounter
    /*$sql_discovered = $conn->prepare(
        "SELECT COUNT(*) FROM Discovered_Animals
         WHERE username=? AND species=?");
    $sql_discovered->bind_param("ss", $username, $animal_species);
    $sql_discovered->execute();
    $sql_discovered->bind_result($discovered_count);
    $sql_discovered->fetch();
    $sql_discovered->close();

    $discovered_time = "0";
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
    }*/

    //Figure out that animal's ID number
    /*$sql_encounters_with_animal = $conn->prepare(
        "SELECT COUNT(*) FROM Animal_Encounter
         WHERE username=? AND species=?");
    $sql_encounters_with_animal->bind_param("ss", $username, $animal_species);
    $sql_encounters_with_animal->execute();
    $sql_encounters_with_animal->bind_result($encounter_count);
    $sql_encounters_with_animal->fetch();
    $sql_encounters_with_animal->close();
    */

    //Calculate Encounter
    $sql_calculate_id = $conn->prepare(
        "SELECT MAX(encounter_id) FROM Animal_Encounter
         WHERE username=?"
    );
    $sql_calculate_id->bind_param("s", $username);
    $sql_calculate_id->execute();
    $sql_calculate_id->bind_result($this_encounter_id);
    $sql_calculate_id->fetch();
    $sql_calculate_id->close();
    $this_encounter_id = $this_encounter_id + 1;	

    $data['animal_id'] = $this_encounter_id;
    $data['animal_species'] = $animal_species;
	$data['health_1'] = $rand_health1;
	$data['health_2'] = $rand_health2;
	$data['health_3'] = $rand_health3;
    $data['size'] = $rand_size;
    $data['age'] = $rand_age;
	$data['weight'] = $rand_weight;

    /*$sql = "SELECT * from Animal WHERE species = '$animal_species'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $rand_health1 = rand(1, 100);
            $rand_health2 = rand(1, 100);
            $rand_health3 = rand(1, 100);
            $rand_size = GenerateRandomNumberInRange($row['min_value'], $row['max_value']);
 	        $rand_weight = GenerateRandomNumberInRange($row['min_weight'], $row['max_weight']);
            $rand_age = GenerateRandomNumberInRange($row['min_age'], $row['max_age']);

            $sql_total_encounters = "SELECT * FROM Animal_Encounter 
                                     WHERE username = '$username' AND
                                     species = '$animal_species'";
            $total_encounters = $conn->query($sql_total_encounters);
            $encounter_id = $total_encounters->num_rows + 1;
            $encounter_id = 1;
	    if ($total_encounters->num_rows == 0)
            {
                $discovered = "discovered";
                $sql_add_encounter = "INSERT INTO Animal_Encounter VALUES(
                    '$username', '$encounter_id', '$animal_species', 
                    '$discovered', '$rand_health', '$rand_health', '$rand_health',
                    NOW() 
                )";     
                $conn->query($sql_add_encounter);
            }
	    else
	    {
		$sql_calc_id = "SELECT MAX(encounter_id) from Animal_Encounter
				        WHERE username='$username'";
		$id_result = $conn->query($sql_calc_id);
		$encounter_id = $id_result->num_rows + 1;	
	    }
            $data['animal_id'] = $encounter_id;
            $data['animal_species'] = $animal_species;
	        $data['health_1'] = $rand_health1;
	        $data['health_2'] = $rand_health2;
	        $data['health_3'] = $rand_health3;
            $data['size'] = $rand_size;
            $data['age'] = $rand_age;
	        $data['weight'] = $rand_weight;
        }
    }    
*/
	echo json_encode($data);

	$conn->close();
?>

