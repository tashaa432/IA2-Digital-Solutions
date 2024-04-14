User
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start or resume the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "_fIpGeMVe[(.sRtb";
$database = "bneTraffic";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Set error message
    $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
    // Redirect to another page
    header("Location: admin/dashboard.php");
    exit();
}

// Query to fetch users with email or phone notifications enabled
$userQuery = "SELECT * FROM users WHERE emailNotifications = true OR phoneNotifications = true";
$userResult = $conn->query($userQuery);

// Check if there are users with email or phone notifications enabled
if ($userResult->num_rows > 0) {
    // Iterate through users
    while ($userRow = $userResult->fetch_assoc()) {
        $email = $userRow['email'];
        $name = $userRow['name'];
        $phone = $userRow['phone'];
        $streetOne = ucwords(strtolower($userRow['streetOne']));
        $streetOneSuburb = ucwords(strtolower($userRow['streetOneSuburb']));
        $streetTwo = ucwords(strtolower($userRow['streetTwo']));
        $streetTwoSuburb = ucwords(strtolower($userRow['streetTwoSuburb']));
        $streetThree = ucwords(strtolower($userRow['streetThree']));
        $streetThreeSuburb = ucwords(strtolower($userRow['streetThreeSuburb']));

        // Query to fetch matching traffic data for the user
        $trafficQuery = "SELECT * FROM Traffic WHERE (roadPrimary = '$streetOne' AND suburb = '$streetOneSuburb') 
                        OR (roadPrimary = '$streetTwo' AND suburb = '$streetTwoSuburb') 
                        OR (roadPrimary = '$streetThree' AND suburb = '$streetThreeSuburb')";
        $trafficResult = $conn->query($trafficQuery);

        // Check if there are matching traffic records
        if ($trafficResult->num_rows > 0) {
            // Prepare notification content
            $notificationContent = '';

            // Add opening lines to the email
            $notificationContent .= "Dear $name,\n\n";
            $notificationContent .= "The below streets have temporary road closures:\n\n";

            // Iterate through matching traffic records
            while ($trafficRow = $trafficResult->fetch_assoc()) {
                // Format date to dd/mm/yyyy
                $startDate = date('d/m/Y', strtotime($trafficRow['startDate']));
                $endDate = date('d/m/Y', strtotime($trafficRow['endDate']));
                
                // Format time to HH:MM
                $startTime = date('H:i', strtotime($trafficRow['startTime']));
                $endTime = date('H:i', strtotime($trafficRow['endTime']));

                // Convert streets and suburb to title case
                $roadPrimary = ucwords(strtolower($trafficRow['roadPrimary']));
                $suburb = ucwords(strtolower($trafficRow['suburb']));
                $crossStreet1 = ucwords(strtolower($trafficRow['1CrossSt']));
                $crossStreet2 = ucwords(strtolower($trafficRow['2crossSt']));

                // Construct notification content with desired formatting
                $notificationContent .= "Start Date: " . $startDate . "\n";
                $notificationContent .= "End Date: " . $endDate . "\n";
                $notificationContent .= "Road: $roadPrimary between $crossStreet1 & $crossStreet2 \n";
                $notificationContent .= "Suburb: " . $suburb . "\n";
                $notificationContent .= "Closure Type: " . $trafficRow['closureType'] . "\n";
                $notificationContent .= "Direction: " . $trafficRow['direction'] . "\n";
                $notificationContent .= "Job Description: " . $trafficRow['jobDesc'] . "\n";
                $notificationContent .= "Start Time: " . $startTime . "\n";
                $notificationContent .= "End Time: " . $endTime . "\n\n";
            }

            // Add copyright notice and unsubscribe link
            $notificationContent .= "To unsubscribe, click the following link: http://localhost/WebpageIA2/manage.php\n";

            // Set additional headers for email
            $emailHeaders = "From: traffic@qld.bne.gov.au" . "\r\n" .
                            "X-Mailer: PHP/" . phpversion();

            // Send email if email notifications are enabled
            if ($userRow['emailNotifications'] == true) {
                // Output email content and recipient's email address for debugging
                echo "Sending email to: $email<br>";
                echo "Email Content:<br>";
                echo nl2br($notificationContent); // Output email content with line breaks preserved
                echo "<br>";

                // For debugging purposes, you can also output the headers
                echo "Email Headers:<br>";
                echo nl2br($emailHeaders); // Output email headers with line breaks preserved
                echo "<br>";

                // Try to send email
                if (mail($email, "Road Closure Notification", $notificationContent, $emailHeaders)) {
                    // Set success message
                    $_SESSION['success_message'] = "CSV uploaded and users notified.";
                    echo "Email sent successfully!<br>";
                } else {
                    // Output error message if email sending fails
                    echo "Failed to send email.<br>";
                }
            }

            // Send text if phone notifications are enabled
            if ($userRow['phoneNotifications'] == true) {
                // Send text here
                $_SESSION['success_message'] = "CSV uploaded and users notified.";
            }
        }
    }
} else {
    // Set success message if no users with notifications enabled
    $_SESSION['success_message'] = "No users with notifications enabled.";
}

// Close connection
$conn->close();

// Redirect to admin dashboard
header("Location: dashboard.php");
exit();

?>