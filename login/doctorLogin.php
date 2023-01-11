<?php require("../templates/header.php"); 
require_once("../config/config.php");

session_start();
$_SESSION["Dusername"] = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
$username = mysqli_real_escape_string($conn,$_POST['username']);
$password = mysqli_real_escape_string($conn,$_POST['password']);

if ($username != "" && $password != ""){

    $sql_query = "select count(*) as userCount from doctor where Daccount='".$username."' and Dpassword='".$password."'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_array($result);

    $count = $row['userCount'];

    if($count > 0){
        $_SESSION["Dusername"] = $username;
        header('Location: ../schedule/schedule.php');
    }else{
        echo "Invalid username and password";
    }

}
}
?>
 
    
<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
	</head>
	<body>
    <div class="wrapper">
        <h2>Login</h2>
             <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control">
        </div>
        <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
        </div>
        <p> Don't have an account? <a href="../register/doctorRegister.php">Register here</a>.</p>


    </div>
	</body>
</html>