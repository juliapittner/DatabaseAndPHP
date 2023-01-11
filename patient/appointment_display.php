<?php
// Initialize the session
session_start();

// Include config file
require_once("../config/config.php");

// Define variables and initialize with empty values
$service = "";
$service_err = "";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: patient_login.php");
    exit;
}

$username = $_SESSION["Pusername"];

$k =$conn ->query("select * from patient where Paccount = '$username'");
$patientData = $k->fetch_assoc();
$patientSSN = $patientData["ssn"];
$networkStatus = $patientData["OHC_network_status"];

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    //insert data into DB
	$appointment = "";

	$appointment = $_REQUEST["appointment"];

	$sql =$conn ->query("SELECT * FROM doctor_schedule WHERE scheduleID = '$appointment'");
	$doctor_schedule = $sql->fetch_assoc();
	$appointment_date = $doctor_schedule["scheduleDay"];
	$time = $doctor_schedule["time"];
	$doc_ID = $doctor_schedule["OHCID"];
	$hospital_ID = $doctor_schedule["hospital_ID"];
	$service_code = $doctor_schedule["serviceType"];
	$billID = uniqid();

    $z=$conn->prepare("insert into appointment (date,time,doc_OHC_ID,hospitalID,patientSSN,billNo,service_code) values (?,?,?,?,?,?,?)");
    $z->bind_param("sssssss",$appointment_date,$time,$doc_ID,$hospital_ID,$patientSSN,$billID,$service_code);
    $z->execute();

	$bill =$conn ->query("SELECT hs.in_net_fee, hs.out_net_fee, ds.doctor_fee_in_network, ds.doctor_fee_out_network FROM hospital_gives_service as
	hs, doctor_gives_service as ds WHERE hs.hospitalID = '$hospital_ID' and hs.service_code = '$service_code' and ds.doc_OHC_ID = '$doc_ID' and ds.service_code = '$service_code'");
	$billData = $bill->fetch_assoc();

	if($networkStatus == "in"){
		$doctor_fee = $billData["doctor_fee_in_network"];
		$hospital_fee = $billData["in_net_fee"];
	}
	else{
		$doctor_fee = $billData["doctor_fee_out_network"];
		$hospital_fee = $billData["out_net_fee"];
	}
	var_dump($billData);
	$total_fee = $doctor_fee + $hospital_fee;

	$z=$conn->prepare("insert into bill (billNo,total_fee,doctor_fee,hospital_fee) values (?,?,?,?)");
    $z->bind_param("ssss",$billID,$total_fee,$doctor_fee,$hospital_fee);
    $z->execute();

	header("location: welcome.php");
    
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
	<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> "method = "POST">
    <div class="wrapper">
		<h2>Available Appointments</h2>
		<?php
		//$conn = new mysqli('localhost', 'root', 'mysql', 'healthcare');
		$service = $_SESSION["service"];

		$sql = "SELECT ds.*, dr.Dfirstname, dr.Dlastname, h.hospitalname FROM doctor_schedule as ds, doctor as dr, hospital as h WHERE dr.OHCNo = ds.OHCID
		and h.hospitalID = ds.hospital_ID and serviceType = '$service'";
		$result = mysqli_query($conn, $sql);
		
        $appointmentOptions="";
    	while($row = mysqli_fetch_array($result)) {
            $appointmentOptions .= "<option value=".$row[0].">Date: ".$row[1]." Time: ".$row[2]." Doctor: ".$row[6]." ".$row[7]." Hospital: ".$row[8]."</option>";
        }
                            
?>
        <div class="col-sm-20">
            <select class="select form-control" id="appointment" name="appointment" required>
                <?php echo $appointmentOptions; ?> 
            </select>
        </div>
	</br>
		<div class="form-group">
			<input type="submit" class="btn btn-primary" id="bookAppointment" value="Book Appointment">
        </div>
	</div>
	</form>
</body>
</html>