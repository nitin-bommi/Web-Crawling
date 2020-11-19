<?php
// Initialize the session
session_start();

include_once("scraper.php")
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$email = $username = $email = "";

// Sending messages/contact form
if(isset($_POST['messagebtn'])){
    require_once "connection.php";
    $email = $_POST["email"];
    // Ignoring ' in strings
    $message = mysqli_real_escape_string($conn, $_POST["message"]);
    $username = $_SESSION["username"];
    $sql = "INSERT INTO Messages (email, message, username)
    VALUES ('$email', '$message', '$username')";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Message sent successfully")</script>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>

<html>
    <head>
        <title>WeatherAppWebScrapper</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">  
        <style>
            .page-header {
                margin: 100px;
                text-align: center;
            }
            .container {
                text-align: center;
                margin: auto;
                width: 450px;
                background-color: #b3b3b1;
                padding: 20px;
                border-radius: 20px;
            }
            .margin {
                margin-top: 20px;
            }
            .centre {
                text-align: center;
            }
            .contact{
                padding-top: 30px;
                background-color: #444442;
                color: #FFFFFF;
                text-align: center;
                margin: auto;
                margin-top: 200px;
                width: 100%; 
            }
        </style>
    </head>
    <body>
        <div class="page-header">
            <h2>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h2>
            <p>
                <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
                <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
            </p>
        </div>
        <div class="container">
            <h1>Weather</h1>
            <form class="margin">
                <fieldset class="form-group">
                    <label for="city">Enter the City Name</label>
                    <input type="text" class="form-control centre" id="city" placeholder="Eg : Hyderabad, Delhi" name="city"  value = "<?php if (array_key_exists('city', $_GET)) {echo $_GET['city'];} ?>">
                </fieldset>  
                <button type="submit" class="btn btn-primary">Get Data</button>
            </form>
            <?php 
                // Displaying the result
                if ($weather) {
                    echo '<div class="alert alert-success" role="alert"> '.$weather.' </div>';
                } else if ($error) {
                    echo '<div class="alert alert-danger" role="alert"> '.$error.' </div>';
                }
            ?>
        </div>
        <div class="contact">
            <div class="row">
                <h1>CONTACT US</h1>
            </div>
            <div class="row">
                <h4>We'd love to hear from you!</h4>
            </div>
            <div class="container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>    
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <textarea type="textarea" name="message" class="form-control" placeholder="Message (upto 1000 characters)" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="messagebtn" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>  
    </body>
</html>