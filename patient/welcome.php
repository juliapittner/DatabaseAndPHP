<?php
require("../templates/header.php"); 
require_once("../config/config.php");


// Check if the user is logged in, if not then redirect him to login page
session_start();

if(!isset($_SESSION["Pusername"])){
    
    header("location: ../login/patientLogin.php");
}
?>
 

 
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; margin: auto;}
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo ($_SESSION["Pusername"]); ?></b>. Welcome to the OHC Patient site.</h1>
    <p>
        <a href="../patient/patientAppointment.php" class="btn btn-danger ml-3">Book an Appointment</a>
		<a href="patient_bills.php" class="btn btn-danger ml-3">View Your Bills</a>
		<a href="patient_logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
</body>
</html>



