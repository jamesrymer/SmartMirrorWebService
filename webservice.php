<?php
// Add database paramaters
$hn = '';
$un = '';
$pw = '';
$db = '';

// Establish connection with database 
$conn = new mysqli($hn, $un, $pw, $db);
if($conn -> connect_error) die($conn->connect_error);

// Retrieve data in the post array and sanatize for database inputs
$postArr = array(

	"homeAddr" 	=> mysqli_real_escape_string($conn,$_POST["homeAddr"]),
	"desAddr1" 	=>  mysqli_real_escape_string($conn,$_POST["desAddr1"]),
	"desAddr2" 	=>  mysqli_real_escape_string($conn,$_POST["desAddr2"]),
	"desAddr3" 	=>  mysqli_real_escape_string($conn,$_POST["desAddr3"]),
	"desAddr4"	=>  mysqli_real_escape_string($conn,$_POST["desAddr4"]),
	"desAddr5" 	=>  mysqli_real_escape_string($conn,$_POST["desAddr5"]),
	"desAddr5" 	=>  mysqli_real_escape_string($conn,$_POST["desAddr5"]),
	"time_format" 	=>  mysqli_real_escape_string($conn,$_POST["time_format"]),
	"temp_format" 	=>  mysqli_real_escape_string($conn,$_POST["temp_format"]),
	"mirrorID"	=> intval(mysqli_real_escape_string($conn,$_POST["mirrorID"])),
	"postPinHash"	=> mysqli_real_escape_string($conn,$_POST["mirrorPinHash"])
	
);

// Format query string
$query = "SELECT saltedPhash, salt FROM mirror WHERE mirrorid = " . $postArr["mirrorID"] ;

// Query the database
$result = $conn->query($query);
if(!$result) die ($conn->connect_error);

// Create associative array from query result
$row = $result->fetch_assoc();

// Create a salted hash with the pin from the POST request and the salt from the database 
$saltedPostPinHash = hash('sha256', $postArr["postPinHash"] . $row["salt"]);

// If the hash matches that in the database, update information in the database
if($saltedPostPinHash == $row["saltedPhash"]){

	$sql = "UPDATE mirror SET home_address='" 
	. $postArr["homeAddr"]
	. "', destination_address_one='"
	. $postArr["desAddr1"] 
	. "',destination_address_two='" 
	. $postArr["desAddr2"] 
	. "',destination_address_three='" 
	. $postArr["desAddr3"] 
	. "',destination_address_four='" 
	. $postArr["desAddr4"] 
	. "',destination_address_five='"
	. $postArr["desAddr5"] 
	. "',time_format='" 
	. $postArr["time_format"]
	. "',temp_format='" 
	. $postArr["temp_format"]
	. "'WHERE mirrorid=" 
	. $postArr["mirrorID"];

	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
} else {
	echo "Invalid Credentials";
}

?>
