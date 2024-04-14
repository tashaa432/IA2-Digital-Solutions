<?php
// Assuming you have a database connection established
// Replace database credentials and table name as necessary
error_reporting(E_ALL); ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "_fIpGeMVe[(.sRtb";
$database = "bneTraffic";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user input
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert user
$sql = "INSERT INTO admins (email, password) VALUES ('$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "User registered successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();