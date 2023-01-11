<?php
// Initialize the session
session_start();

// Include config file
require_once("../config/config.php");

// Define variables and initialize with empty values
$service =  $id = "";
$service_err = "";
$id = $_SESSION["id"];

$h=$conn->query("select * from service;");
$serviceData = $h->fetch_all();
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	// Checks for and reqires service code
	if(empty(trim($_POST["service"]))){
        $service_err = "Please enter a service code.";
    } elseif(!preg_match('/^[A-Z0-9]+$/', trim($_POST["service"]))){
        $service_err = "Service Code can only contain uppercase letters, and numbers.";
    } else {
		$service = trim($_POST["service"]);
	}
	// Checks if all inputs are valid
	if(empty($service_err)) {
		// Put variables into session so that the other page can use them
		$_SESSION["service"] = $service;
		$_SESSION["id"] = $id;	
		header("location: ../patient/appointment_display.php");
	}
}
	
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book an Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; margin: auto;}
		.wrapper{ height: 200px; width: 360px; padding: 20px; line-height: 20px; margin:auto;}
    </style>
</head>
<body>
    <div class="wrapper">
		<h2>Book an Appointment</h2>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="form-group form-group-lg">
                                  <label class="control-label col-sm-10 requiredField" for="date">
                                   Day
                                  
                                  </label>
                                  <div class="col-sm-10">
                                   <select class="select form-control" id="date" name="date" required>
                                    <option value="Tomorrow">
                                     Tomorrow
                                    </option>
                
                                   </select>
                                  </div>
                                 </div>
								<div class = "form-group form-group-lg">
		<label class="control-label col-sm-10 requiredField" for="serviceType">
                                   Select a Service
                                   </label>
                                </div>

                                <?php //var_dump($hospitalData);?>

                                <?php 
                                $serviceOptions="";
                                for ($i=0;$i<count($serviceData);$i++){
                                    $serviceOptions .= "<option value=".$serviceData[$i][0].">".$serviceData[$i][1]."</option>";
                                }
                                ?>

                                <div class="col-sm-10">
                                   <select class="select form-control" id="service" name="service" required>
                                        <?php echo $serviceOptions; ?> 
                                   </select>
                                </div>
								
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="View Available Times">
                		<input type="reset" class="btn btn-secondary ml-2" value="Reset">
            	</div>
		</form>
	</div>
</body>
</html>