<?php
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

// Get input from the user
$input = $_GET['input'];

// Escape special characters to prevent SQL injection
$input = mysqli_real_escape_string($conn, $input);

// Query to fetch filtered streets based on input
$sql = "SELECT StreetName, StreetType, Suburb FROM Streets 
        WHERE StreetName LIKE '%$input%' 
        OR StreetType LIKE '%$input%' 
        OR Suburb LIKE '%$input%'";

$result = $conn->query($sql);

if ($result === false) {
    // Error handling for query execution
    echo "<div class='autocomplete-option'>Error retrieving streets</div>";
} elseif ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Format the street fields
        $streetName = ucfirst(strtolower($row["StreetName"])); // Capitalize first letter of street name
        $streetType = $row["StreetType"];
        $suburb = ucfirst(strtolower($row["Suburb"])); // Capitalize first letter of suburb
        echo "<div class='autocomplete-option'>" . $streetName . " " . $streetType . ", " . $suburb . "</div>";
    }
} else {
    echo "<div class='autocomplete-option'>No streets found</div>";
}

// Close connection
$conn->close();
