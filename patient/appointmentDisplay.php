<?php
// Initialize the session
require("../templates/header.php"); 
session_start();

// Include config file
require_once("../config/config.php");

// Define variables and initialize with empty values

$service_err = "";
$service = "";
$date = "";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["Pusername"])){
    header("location: ../patient/patientLogin.php");
    
}

 
	
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Appointments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; margin: auto;}
		.wrapper{ height: 200px; width: 400px; padding: 20px; line-height: 20px; margin:auto;}
    </style>
</head>
<body>
    <div class="wrapper">
		<h2>Available Appointments</h2>
		<?php
		if(!isset($_SESSION["service"])){
			$service = $_SESSION["service"];

		}
		//$service = $_SESSION["service"];
		if(!isset($_SESSION["date"])){
			$date = $_SESSION["date"];

		}
		
		
		
		$sql = "SELECT * FROM doctor_schedule WHERE serviceType = '$service' and scheduleDay = '$date'";
		$result = mysqli_query($conn, $sql);
		while($row = mysqli_fetch_array($result)) {
			echo "Date: ", $row[1], "Time: ", $row[2], "Service Code: ", $row[3], "Doctor ID: ", $row[4],".<br>";
		}
		?>
	</div>
</body>
</html>