<?php
// Add database paramaters
$hn = '';
$un = '';
$pw = '';
$db = '';

// Establish connection with database 
$conn = new mysqli($hn, $un, $pw, $db);
if($conn -> connect_error) die($conn->connect_error);

// Retrieve data in the post array 
$postArr = array(
	"mirrorID"	=> $conn,$_POST["mirrorID"],
	"postPinHash"	=> $conn,$_POST["mirrorPinHash"]
);

// Format query string
$query = "SELECT * FROM mirror WHERE mirrorid = " . $postArr["mirrorID"] ;

// Query the database
$result = $conn->query($query);
if(!$result) die ($conn->connect_error);

// Create associative array from query result
$row = $result->fetch_assoc();

// Create a salted hash with the pin from the POST request and the salt from the database 
$saltedPostPinHash = hash('sha256', $postArr["postPinHash"] . $row["salt"]);

// If the hash matches that in the database, echo requested information in json format
if($saltedPostPinHash == $row["saltedPhash"]){

	$arr = array(

		"homeAddr" => $row["home_address"],
		"desAddr1" => $row["destination_address_one"],
		"desAddr2" => $row["destination_address_two"],
		"desAddr3" => $row["destination_address_three"],
		"desAddr4" => $row["destination_address_four"],
		"desAddr5" => $row["destination_address_five"],
		"time_format" => $row["time_format"],
		"temp_format" => $row["temp_format"]
		
	);
	echo json_encode($arr);

} else {
	echo "Invalid Credentials";
}

?>
