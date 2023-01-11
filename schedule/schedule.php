<?php 
require("../templates/header.php"); 
require_once("../config/config.php");
global $conn;

session_start();
if(!isset($_SESSION["Dusername"])){
    
    header("location: ../login/doctorLogin.php");
}

$username = $_SESSION["Dusername"];


$m = $conn ->query("select * from hospital");
$hospitalData = $m->fetch_all();

$h=$conn->query("select * from service;");
$serviceData = $h->fetch_all();

$k =$conn ->query("select * from doctor where Daccount = '$username'");
$doctorData = $k->fetch_assoc();
$doctorID = $doctorData["OHCNo"];
$doctor_first_name = $doctorData["Dfirstname"];
$dcotor_last_name = $doctorData["Dlastname"];

if (isSet($_REQUEST["scheduleday"])){
    //insert data into DB
    $z=$conn->prepare("insert into doctor_schedule (scheduleday,time, serviceType, OHCID, hospital_ID) values (?,?,?, ?, ?)");
    $z->bind_param("sssss",$_REQUEST["scheduleday"],$_REQUEST["starttime"],$_REQUEST["serviceType"], $doctorID, $_REQUEST["hospital"]);
    $z->execute();
    
}
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
    <script src="js/jquery.min.js"></script>

<!-- Bootstrap library -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="bootstrap/js/bootstrap.min.js"></script>
<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="js/bootstrap-datetimepicker.min.js"></script>
        <h1> Welcome Dr. <?php echo $doctor_first_name ?> <?php echo $dcotor_last_name ?> </h1>
    </head>
<body>
    <div class= "panel-heading">
        <h2> Add Schedule</h2>
    </div>
    <div class="panel-body">
                        <!-- panel content start -->
                            <div class="bootstrap-iso">
                             <div class="container-fluid">
                              <div class="row">
                               <div class="col-md-12 col-sm-12 col-xs-12">
                                <form class="form-horizontal" method="post">
                                 <div class="form-group form-group-lg">
                                  

                                   </div>
                                  </div>
                                 </div>
                                 <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="scheduleday">
                                   Day
                                  
                                  </label>
                                  <div class="col-sm-10">
                                   <select class="select form-control" id="scheduleday" name="scheduleday" required>
                                    <option value="Tomorrow">
                                     Tomorrow
                                    </option>
                
                                   </select>
                                  </div>
                                 </div>
                                 <div class="form-group form-group-lg">
                                 <?php
                                 
                                 $times=[];
                                 $donationtime=Date('H:i:s');
                                 $startTime=7;
                                 $numTimes=14;
                                 $optionsHtml="";
                                 for($i=0;$i<12;$i++){
                                    $timeStringMilitary=$startTime.":00";
                                    $timeCivilian=$timeStringMilitary . " AM";
                                    if($startTime>=13){
                                        $timeCivilian = $startTime-12 .":00 PM";
                                    } 
                                    $optionsHtml .= "<option value=".$timeStringMilitary.">".$timeCivilian."</option>";
                                    $startTime++;
                                 }

                                 ?>
                                  <label class="control-label col-sm-2 requiredField" for="starttime">
                                   Select A Time
                                  </label>
                                  <div class="col-sm-10">
                                   <select class="select form-control" id="starttime" name="starttime" required>
                                   <?php echo $optionsHtml; ?>
                                   </select>
                                  </div>
                                  
        
                                 <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="serviceType">
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
                                   <select class="select form-control" id="serviceType" name="serviceType" required>
                                        <?php echo $serviceOptions; ?> 
                                   </select>
                                </div>

                                <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="hospital">
                                   Select A Hospital
                                  
                                  </label>
                                </div>
                                <?php 
                                $hospitalOptions ="";
                                for ($i=0;$i<count($hospitalData);$i++){
                                    $hospitalOptions .= "<option value=".$hospitalData[$i][0].">".$hospitalData[$i][1]."</option>";
                                }
                                ?>
                                  <div class="col-sm-10">
                                   <select class="select form-control" id="hospital" name="hospital" required>
                                    
                                    <?php echo $hospitalOptions; ?>
                                   </select>
                                  </div>
                                 
                                  
                           
                                <br><br>
                                 <div class="form-group">
                                  <div class="col-sm-10 col-sm-offset-2">
                                   <button class="btn btn-primary " name="submit" type="submit">
                                    Submit
                                   </button>
                                  </div>
                                 </div>
                                </form>
                               </div>
                              </div>
                             </div>
                            </div>                        
                       
                        </div>
                    </div>
                    

                     
</html>