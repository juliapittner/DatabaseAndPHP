<?php 
require("../templates/header.php"); 
 require_once("../config/config.php");

 $username = $password = $confirm_password = $first_name = $last_name = $gender = "";

$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $gender_err = "";

 

// Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){

 

    // Validate username

    if(empty(trim($_POST["username"]))){

        $username_err = "Please enter a username.";

    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){

        $username_err = "Username can only contain letters, numbers, and underscores.";

    } else{

        // Prepare a select statement

        $sql = "SELECT OHCNo FROM doctor WHERE Daccount = ?";

        

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

    

    // Check input errors before inserting in database

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($gender_err)){

        

        // Prepare an insert statement

        $sql = "INSERT INTO doctor (Dfirstname, Dlastname, Daccount, Dpassword, Gender) VALUES (?, ?, ?, ?, ?)";

         

        if($stmt = mysqli_prepare($conn, $sql)){

            // Bind variables to the prepared statement as parameters

            mysqli_stmt_bind_param($stmt, "sssss",$param_docotor_first_name, $param_doctor_last_name, $param_username, $param_password, $param_gender);

            

            // Set parameters

            $param_username = $username;

            //$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

		$param_password = $password;

            $param_docotor_first_name = $first_name;

		$param_doctor_last_name = $last_name;

		$param_gender = $gender;

            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){

                // Redirect to login page

                header("location: ../login/doctorLogin.php");

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

                <input type="submit" class="btn btn-primary" value="Submit">

                <input type="reset" class="btn btn-secondary ml-2" value="Reset">

            </div>

            <p>Already have an account? <a href="login.php">Login here</a>.</p>

        </form>

    </div>    

</body>

</html>