<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if($_SESSION["type"]=='U'){
    header("location: user.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style type="text/css">
        .page-header {
            text-align: center;
            background-color: #444442;
            color: #fff;
        }
        .container {
            text-align: center;
            margin-top: 200px;
            width: 450px;
        }
        .margin {
            margin-top: 20px;
        }
        .centre {
            text-align: center;
        }
        body { 
            font: 14px sans-serif; 
        }
        .wrapper { 
            width: 350px; 
            padding: 20px; 
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h2>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h2>
        <br>
        <form method="post"> 
            <input type="submit" class="btn btn-success" name="adduser" value="Add User" />    
            <input type="submit" class="btn btn-danger" name="deleteuser" value="Delete User" /><br><br>             
            <input type="submit" class="btn btn-primary" name="showuser" value="Show Users" /> 
            <input type="submit" class="btn btn-primary" name="showmessages" value="Show Messages" /> 
            <input type="submit" class="btn btn-primary" name="download" value="Download Data" />
        </form><br><br>
        <button class="btn btn-warning" onclick="WeatherReport()">View Weather report</button>
        <button class="btn btn-warning" onclick="logOut()">Log Out</button><br>
    </div>

<?php
include('connection.php');

// Define variables and initialize with empty values
$username = $password = $confirm_password = $phone = "";
$username_err = $password_err = $confirm_password_err = $phone_err = "";

if(isset($_POST['add'])){
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
                    echo '<div class="container"><div class="alert alert-danger" role="alert"> User with that username exists! </div></div>';
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

        // Prepare an insert statement
        $sql = "INSERT INTO ITLabexerciseusers (username, password, phone, type)
                VALUES ('$username', '$password', '$phone', '$type')";
         
        if (mysqli_query($conn, $sql)) {
            echo '<div class="container"><div class="alert alert-success" role="alert"> User added successfully! </div></div>';
        } else {
            echo '<div class="container"><div class="alert alert-danger" role="alert"> User with that username exists! </div></div>';
        }
    }
}

if(isset($_POST['delete'])){
    // Storing the form inputs to variables.
    $username = trim($_POST["username"]);
    $type = "";
        if($_POST["type"] == 'user') {
            $type = "U"; 
        } else {
            $type = "A";
        }

    // Checking if there are records with that email.
    $sql = "SELECT type FROM itlabexerciseusers WHERE username='$username'";
    $out = mysqli_query($conn, $sql);

    // If there is atleast one record then email is taken.
    if(mysqli_num_rows($out) == 0){
        echo '<div class="container"><div class="alert alert-warning" role="alert"> No person with that username exists </div></div>';
    }
    // Else the data is inserted in database.
    else{
        $row = mysqli_fetch_array($out, MYSQLI_ASSOC);
        if($type ==  $row['type']){
            $sql = "DELETE FROM itlabexerciseusers WHERE username = '$username'";
            if(mysqli_query($conn, $sql) === TRUE) {
                echo '<div class="container"><div class="alert alert-info" role="alert"> ' . $username . ' deleted </div></div>';
            }else {
                echo "Error: " . $conn->error;
            }
        }
        else{
            echo '<div class="container"><div class="alert alert-danger" role="alert"> Category ' . $type . ' didnt match the registered data </div></div>';
        }
        
    }
}

// Add user form
if(isset($_POST['adduser'])) { 
    echo "<div class='wrapper'>
    <h2>Add User</h2>
    <p>Please fill this form to add an user.</p>
    <form method='post'>
        <div class='form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>
            <label>Username</label>
            <input type='text' name='username' class='form-control' required>
            <span class='help-block'><?php echo $username_err; ?></span>
        </div>    
        <div class='form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>
            <label>Password</label>
            <input type='password' name='password' class='form-control' required>
            <span class='help-block'><?php echo $password_err; ?></span>
        </div>
        <div class='form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>
            <label>Phone Number</label>
            <input type='tel' name='phone' placeholder='0123456789' pattern='[0-9]{10}' class='form-control' required>
            <span class='help-block'><?php echo $phone_err; ?></span>
        </div>
        <div class='form-group'>
            <label class='radio-inline'><input type='radio' name='type' value='user' checked>User</label>
            <label class='radio-inline'><input type='radio' name='type' value='admin'>Admin</label>
        </div>
        <div class='form-group'>
            <input type='submit' name='add' class='btn btn-primary' value='Add User'>
            <input type='reset' class='btn btn-default' value='Reset'>
        </div>
    </form>
</div>";
}

if(isset($_POST['deleteuser'])) { 
    echo "<div class='wrapper'>
    <h2>Delete User</h2>
    <p>Please fill this form to delete an user.</p>
    <form method='post'>
        <div class='form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>
            <label>Username</label>
            <input type='text' name='username' class='form-control'>
            <span class='help-block'><?php echo $username_err; ?></span>
        </div>
        <div class='form-group'>
            <label class='radio-inline'><input type='radio' name='type' value='user' checked>User</label>
            <label class='radio-inline'><input type='radio' name='type' value='admin'>Admin</label>
        </div>
        <div class='form-group'>
            <input type='submit' name='delete' class='btn btn-primary' value='Delete User'>
            <input type='reset' class='btn btn-default' value='Reset'>
        </div>
    </form>
</div>";
}

// Displaying the users
if(isset($_POST['showuser'])) {
    $sql = "SELECT username, phone, type FROM itlabexerciseusers";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<div><div class='container'><table class='table'><tr><td><b>Email</b></td><td><b>Phone</b></td><td><b>Category</b></td></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["username"]. "</td><td>" .$row["phone"]. "</td><td>" . $row["type"]. "</td></tr>";
        }
        echo "</table></div></div>";
    } else {
        echo "0 results";
    }
}

// Displaying the messages
if(isset($_POST['showmessages'])){
    $sql = "SELECT username, message, email FROM Messages";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<div><div class='container'><table class='table'><tr><td><b>Username</b></td><td><b>Message</b></td><td><b>Email</b></td></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["username"]. "</td><td>" .$row["message"]. "</td><td>" . $row["email"]. "</td></tr>";
        }
        echo "</table></div></div>";
    } else {
        echo "0 results";
    }
}

// Downloading the data in json format
if(isset($_POST['download'])) {
    $sql = "SELECT *FROM itlabexerciseusers";
    $result = $conn->query($sql);
    $myfile = fopen("file.json", "w") or die("Unable to open file!");
    fwrite($myfile, "[\n");
    foreach($result as $row) {
        $x['id'] = $row['id'];
        $x['username'] = $row['username'];
        $x['phone'] = $row['phone'];
        $x['type'] = $row['type'];
        $x['created_at'] = $row['created_at'];
        fwrite($myfile, json_encode($x));
        fwrite($myfile, ",\n");
    }
    fwrite($myfile, "]");
    fclose($myfile);
    echo '<div class="container"><div class="alert alert-success" role="alert"> Data downloaded successfully! </div></div>';
}
?>
    <script>
        function logOut() {
            // logout.php file removes the stored cookie.
            window.location.href = "logout.php";
        }
        function WeatherReport() {
            // logout.php file removes the stored cookie.
            window.location.href = "user.php";
        }
    </script>
</body>
</html>