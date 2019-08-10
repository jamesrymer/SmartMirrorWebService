<?php
// Add database paramaters
$hn = '';
$un = '';
$pw = '';
$db = '';

// Establish connection with database 
$conn = new mysqli($hn, $un, $pw, $db);
if($conn -> connect_error) die($conn->connect_error);

// Generate psuedo random salt
$str=rand(); 
$salt = md5($str);

// Create hashed salt with the pin hash in the POST array
$saltedHash = hash('sha256', $_POST["phash"] . $salt );

// Format query string
$query = "INSERT INTO mirror (home_address, destination_address_one, destination_address_two, destination_address_three, destination_address_four, destination_address_five, time_format, temp_format, saltedPhash, salt)
VALUES ('', '', '', '', '', '0', '0', '0', '$saltedHash', '$salt')";

// Querry the database
if ($conn->query($query) === TRUE) {
	// Return last created ID
	$last_id = $conn->insert_id;
	echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
	echo "Error: " . $query . "<br>" . $conn->error;
}

?>