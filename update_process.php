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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $phoneNotifications = isset($_POST['phoneNotifications']) ? 1 : 0;
    $emailNotifications = isset($_POST['emailNotifications']) ? 1 : 0;

    // Extract street and suburb for each notification street
    $streetOneParts = isset($_POST['street1']) ? explode(", ", $_POST['street1']) : array("", "");
    $streetTwoParts = isset($_POST['street2']) ? explode(", ", $_POST['street2']) : array("", "");
    $streetThreeParts = isset($_POST['street3']) ? explode(", ", $_POST['street3']) : array("", "");

    $streetOne = $streetOneParts[0];
    $streetOneSuburb = isset($streetOneParts[1]) ? $streetOneParts[1] : "";
    $streetTwo = $streetTwoParts[0];
    $streetTwoSuburb = isset($streetTwoParts[1]) ? $streetTwoParts[1] : "";
    $streetThree = $streetThreeParts[0];
    $streetThreeSuburb = isset($streetThreeParts[1]) ? $streetThreeParts[1] : "";

    // Get database connection
    $conn = get_connection();

    // Prepare SQL statement to update user data
    $sql = "UPDATE Users SET name=?, address=?, phoneNotifications=?, emailNotifications=?, streetOne=?, streetOneSuburb=?, streetTwo=?, streetTwoSuburb=?, streetThree=?, streetThreeSuburb=? WHERE email=? AND phone=?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssssss", $name, $address, $phoneNotifications, $emailNotifications, $streetOne, $streetOneSuburb, $streetTwo, $streetTwoSuburb, $streetThree, $streetThreeSuburb, $email, $phone);

    // Execute the statement
    if ($stmt->execute()) {
        // Set success message
        $_SESSION['success_message'] = "Your subscription details have been successfully updated.";
    } else {
        // Log error
        error_log("Update process error: " . $stmt->error);

        // Set error message
        $_SESSION['error_message'] = "An error occurred while updating your subscription details.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the manage page
    header("Location: manage.php");
    exit();
} else {
    // Do nothing if accessed directly without form submission
    // This block can be used for displaying an error message or any other functionality
    // Redirecting is not necessary here
}
?>
