<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ITLAB";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if(!$conn) {
    die("<br>Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS ITLAB";
if (!mysqli_query($conn, $sql)) {
    echo "<br>Error creating database: " . mysqli_error($conn);
}

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if(!$conn) {
    die("<br>Connection failed: " . mysqli_connect_error());
}

// Create tables
$sql = "CREATE TABLE IF NOT EXISTS ITLABexerciseusers(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    phone CHAR(10) NOT NULL,
    type CHAR NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";
if (!mysqli_query($conn, $sql)) {
    echo "<br>Error creating table: " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS Messages( 
    email VARCHAR(50) NOT NULL ,
    message VARCHAR(1000) NOT NULL , 
    username VARCHAR(50) NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP 
);";
    
if (!mysqli_query($conn, $sql)) {
    echo "<br>Error creating table: " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS data(
    city VARCHAR(20) NOT NULL,
    report VARCHAR(500) NOT NULL,
    queried_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

if (!mysqli_query($conn, $sql)) {
    echo "<br>Error creating table: " . mysqli_error($conn);
}

?>