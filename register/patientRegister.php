<?php 
require("../templates/header.php"); 
 require_once("../config/config.php");

 $username = $password = $confirm_password = $first_name = $last_name = $gender = $age = $ssn = $address = $network_status = "";

$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $gender_err = $age_err = $ssn_err = $address_err = $network_status_err = "";

 

// Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){

 

    // Validate username

    if(empty(trim($_POST["username"]))){

        $username_err = "Please enter a username.";

    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){

        $username_err = "Username can only contain letters, numbers, and underscores.";

    } else{

        // Prepare a select statement

        $sql = "SELECT ssn FROM patient WHERE Paccount = ?";

        

        if($stmt = mysqli_prepare($conn, $sql)){

            // Bind variables to the prepared statement as parameters

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            

            // Set parameters

            $param_username = trim($_POST["username"]);

            

            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){

                /* store result */

                mysqli_stmt_store_result($stmt);

                

                if(mysqli_stmt_num_rows($stmt) == 1){

                    $username_err = "This username is already taken.";

                } else{

                    $username = trim($_POST["username"]);

                }

            } else{

                echo "Oops! Something went wrong. Please try again later.";

            }



            // Close statement

            mysqli_stmt_close($stmt);

        }

    }

    

    // Validate password

    if(empty(trim($_POST["password"]))){

        $password_err = "Please enter a password.";     

    } elseif(strlen(trim($_POST["password"])) < 6){

        $password_err = "Password must have atleast 6 characters.";

    } else{

        $password = trim($_POST["password"]);

    }

    

    // Validate confirm password

    if(empty(trim($_POST["confirm_password"]))){

        $confirm_password_err = "Please confirm password.";     

    } else{

        $confirm_password = trim($_POST["confirm_password"]);

        if(empty($password_err) && ($password != $confirm_password)){

            $confirm_password_err = "Password did not match.";

        }

    }



    //validate doctor first name

    if(empty(trim($_POST["first_name"]))){

        $first_name_err = "Please enter your first name.";     

    } else{

        $first_name = trim($_POST["first_name"]);

    }

    

    //validate doctor last name

    if(empty(trim($_POST["last_name"]))){

        $last_name_err = "Please enter your last name.";     

    } else{

        $last_name = trim($_POST["last_name"]);

    }

    //choose the gender

    if(empty(trim($_POST["gender"]))){

	  $gender_err = "Please choose you gender.";

    } else{

        $gender = trim($_POST["gender"]);

    }

    if(empty(trim($_POST["age"]))){

        $age_err = "Please enter your age.";
  
      } else{
  
          $age = trim($_POST["age"]);
  
      }

      if(empty(trim($_POST["address"]))){

        $address_err = "Please enter your address.";
  
      } else{
  
          $address = trim($_POST["address"]);
  
      }

      if(empty(trim($_POST["network_status"]))){

        $network_status_err = "Please enter your network status.";
  
      } else{
  
          $network_status = trim($_POST["gender"]);
  
      }

      if(empty(trim($_POST["ssn"]))){

        $ssn_err = "Please enter your social security number.";
  
      } else{
  
          $ssn = trim($_POST["ssn"]);
  
      }
  
  

    

    // Check input errors before inserting in database

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($gender_err) && empty($age_err) && empty($network_status_err) && empty($ssn_err) && empty($address_err)){

        

        // Prepare an insert statement

        $sql = "INSERT INTO patient (first_name, last_name, Paccount, Ppassword, gender, age, ssn, address, OHC_network_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

         

        if($stmt = mysqli_prepare($conn, $sql)){

            // Bind variables to the prepared statement as parameters

            mysqli_stmt_bind_param($stmt, "sssssssss", $param_patient_first_name, $param_patient_last_name, $param_username, $param_password, $param_gender, $param_age, $param_ssn, $param_address, $param_network);

            

            // Set parameters

            $param_username = $username;

            //$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

		$param_password = $password;

            $param_patient_first_name = $first_name;

		$param_patient_last_name = $last_name;

		$param_gender = $gender;

        $param_address = $address;

        $param_age = $age;

        $param_ssn = $ssn;

        $param_network = $network_status;

            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){

                // Redirect to login page

                header("location: ../login/patientLogin.php");

            } else{

                echo "Oops! Something went wrong. Please try again later.";

            }



            // Close statement

            mysqli_stmt_close($stmt);

        }

    }

    

    // Close connection

    mysqli_close($conn);

}

?>

 

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Sign Up</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>

        body{ font: 14px sans-serif; }

        .wrapper{ width: 360px; padding: 20px; }

    </style>

</head>

<body>

    <div class="wrapper">

        <h2>Register</h2>

        <p>Please fill this form to create an account.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">

                <label>Username</label>

                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">

                <span class="invalid-feedback"><?php echo $username_err; ?></span>

            </div>    

            <div class="form-group">

                <label>Password</label>

                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">

                <span class="invalid-feedback"><?php echo $password_err; ?></span>

            </div>

            <div class="form-group">

                <label>Confirm Password</label>

                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">

                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>

            </div>

		<div class="form-group">

                <label>First Name</label>

                <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">

                <span class="invalid-feedback"><?php echo $first_name_err; ?></span>

            </div>

		<div class="form-group">

                <label>Last Name</label>

                <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">

                <span class="invalid-feedback"><?php echo $last_name_err; ?></span>

            </div>

		<div class="form-group">

			<label>Gender</label>

			<select name="gender"> 

     				<option>Male</option>

     				<option>Female</option>

 			</select>

		</div>

        <div class="form-group">

                <label>Age</label>

                <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">

                <span class="invalid-feedback"><?php echo $age_err; ?></span>

            </div>

            <div class="form-group">

                <label>Address</label>

                <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">

                <span class="invalid-feedback"><?php echo $address_err; ?></span>

            </div>

            <div class="form-group">

                <label>Social Secuirty Number</label>

                <input type="text" name="ssn" class="form-control <?php echo (!empty($ssn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">

                <span class="invalid-feedback"><?php echo $ssn_err; ?></span>

            </div>
            <div class="form-group">

			<label>Network Status</label>

			<select name="network_status"> 

     				<option>In</option>

     				<option>Out</option>

 			</select>

		</div>


            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="Submit">

                <input type="reset" class="btn btn-secondary ml-2" value="Reset">

            </div>

            <p>Already have an account? <a href="login.php">Login here</a>.</p>

        </form>

    </div>    

</body>

</html>