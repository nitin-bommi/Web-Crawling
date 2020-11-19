<?php

require_once "connection.php";

// Function to generate a random password of length 10
function generatePassword() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);
    for ($i = 0, $result = ''; $i < 10; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }
    return $result;
}

$username = $phone = $username_err = $phone_err = $new_pass = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Check if phone number is empty
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter your phone number.";
    } else{
        $phone = trim($_POST["phone"]);
    }
    if(empty($username_err) && empty($phone_err)) {
        $sql = "SELECT id, username, phone FROM ITLabexerciseusers WHERE username = '$username' AND phone = '$phone'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if($username == $row["username"] && $phone == $row["phone"]) {
                $new_pass =  generatePassword();
                echo "<script>alert('$new_pass');</script>";
                $new_pass = md5($new_pass);
                $id = $row["id"];
                $sql = "UPDATE ITLabexerciseusers SET password = '$new_pass' WHERE id = $id";
                mysqli_query($conn, $sql);
            }
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; margin: auto;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Forgot Password</h2>
        <p>Please verify to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <label>Phone</label>
                <input type="password" name="phone" class="form-control">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="login.php">Cancel</a>
            </div>
            <p><a href="login.php">Login here</a></p>
        </form>
    </div>    
</body>
</html>