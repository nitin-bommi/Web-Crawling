<?php
require_once "connection.php";
// Userame from GET request
$username = $_GET["username"]; 
// Select the tuple/row from the databse
$sql = "SELECT * FROM ITLABexerciseusers WHERE username = '".$username."'";
$out = mysqli_query($conn, $sql);
// Return appropriate result
if(mysqli_num_rows($out) == 0) {
    echo "Username Available";
} else {
    echo "Username taken";
}
mysqli_close($conn);
?>