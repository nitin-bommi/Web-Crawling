<?php

require_once "connection.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $phone = "";
$username_err = $password_err = $confirm_password_err = $phone_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT USERNAME FROM ITLABexerciseusers WHERE USERNAME = ?";
        
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
    if(empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate phone number
    if(empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($phone_err)){

        $type = "";
        if($_POST["type"] == 'user') {
            $type = "U"; 
        } else {
            $type = "A";
        }

        $password = md5($password);

        // Prepare an insert statement
        $sql = "INSERT INTO ITLabexerciseusers (username, password, phone, type)
                VALUES ('$username', '$password', '$phone', '$type')";
         
        if (mysqli_query($conn, $sql)) {
                header("location: login.php");
        } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; margin: auto;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>" onchange="showUser(this.value)">
                <span class="help-block" id="txtHint"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <label>Phone Number</label>
                <input type="tel" name="phone" placeholder="10 digits" pattern="[0-9]{10}" class="form-control">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <label class="radio-inline"><input type="radio" name="type" value="user" checked>User</label>
                <label class="radio-inline"><input type="radio" name="type" value="admin">Admin</label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>  
    <script>
        function showUser(str) {
            if (str == "") {
                document.getElementById("username").innerHTML = "";
                return;
            } else {
                if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var result = this.responseText
                    if (result == "Username Available") {
                        document.getElementById("txtHint").style.color = "green";
                    } else {
                        document.getElementById("txtHint").style.color = "red";
                    }
                    document.getElementById("txtHint").innerHTML = result;
                }
            };
            xmlhttp.open("GET", "check.php?username="+str, true);
            xmlhttp.send();
            }
        }
    </script>
</body>
</html>