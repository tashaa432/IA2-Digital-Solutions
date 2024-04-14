<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start or resume the session

// Database connection details
$servername = "localhost";
$username = "root";
$password = "_fIpGeMVe[(.sRtb";
$database = "bneTraffic";

// Convert date format from 'YYYY-MM-DD' to 'YYYY-MM-DD'
function convertDateFormat($date) {
    if (empty($date)) {
        return null;
    }

    return $date;
}

// Check if form is submitted and agreement checkbox is checked
if(isset($_POST["submit"]) && isset($_POST["agreement"])) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete all existing data
    $deleteQuery = "DELETE FROM Traffic";
    if ($conn->query($deleteQuery) === FALSE) {
        die("Error deleting data: " . $conn->error);
    }

    // Check if a CSV file is uploaded
    if(isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == 0) {
        $file = $_FILES["csvFile"]["tmp_name"];

        // Read CSV file
        $csvData = array_map('str_getcsv', file($file));

        // Remove the first row (headers)
        array_shift($csvData);

        // Prepare statement for inserting data
        $stmt = $conn->prepare("INSERT INTO Traffic (_id, startDate, endDate, roadPrimary, 1CrossSt, 2crossSt, suburb, ward, closureType, direction, jobDesc, period, startTime, endTime, contact, certificate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Check if statement preparation failed
        if (!$stmt) {
            die("Statement preparation failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ssssssssssssssss", $id, $startDate, $endDate, $roadPrimary, $crossSt1, $crossSt2, $suburb, $ward, $closureType, $direction, $jobDesc, $period, $startTime, $endTime, $contact, $certificate);

        // Iterate through CSV data
        foreach($csvData as $row) {
            // Assign CSV data to variables
            $row = array_map(function($value) {
                return $value !== "" ? $value : NULL;
            }, $row);

            // Extract data from the row
            list($id, $startDate, $endDate, $roadPrimary, $crossSt1, $crossSt2, $suburb, $ward, $closureType, $direction, $jobDesc, $period, $startTime, $endTime, $contact, $certificate) = $row;

            // Execute the prepared statement
            if (!$stmt->execute()) {
                // Redirect to dashboard.php with an error message
                $_SESSION['error'] = "Error inserting data: " . $stmt->error;
                header("Location: dashboard.php");
                exit();
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $conn->close();

        // Redirect to the notify.php file
        $_SESSION['success_message'] = "CSV uploaded. No users with matching notifications found.";
        header("Location: notify.php");
        exit();
    } else {
        // Redirect to dashboard.php with an error message
        $_SESSION['error'] = "Error uploading CSV file!";
        header("Location: dashboard.php");
        exit();
    }
} else {
    // Redirect to dashboard.php with an error message
    $_SESSION['error'] = "Form not submitted or agreement not checked!";
    header("Location: dashboard.php");
    exit();
}
?>
