<?php
session_start();

// Function to establish database connection
function get_connection() {
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

    return $conn;
}

// Check if email and phone are provided via GET parameters
if (!isset($_GET['email']) || !isset($_GET['phone'])) {
    // Redirect to manage page if email and phone are not provided
    header("Location: manage.php");
    exit();
}

// Retrieve email and phone from GET parameters
$email = $_GET['email'];
$phone = $_GET['phone'];

// Get database connection
$conn = get_connection();

// Prepare SQL statement to delete user
$sql = "DELETE FROM Users WHERE email=? AND phone=?";

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $phone);

// Execute the statement
if ($stmt->execute()) {
    // Set success message
    $_SESSION['success_message'] = "Your subscription has been successfully removed.";
} else {
    // Set error message
    $_SESSION['error_message'] = "An error occurred while removing your subscription: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();

// Redirect to manage page
header("Location: manage.php");
exit();
?>
