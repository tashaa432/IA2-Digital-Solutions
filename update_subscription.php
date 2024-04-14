<?php
session_start();

// Initialize variables
$error_message = "";
$success_message = "";

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
    // Redirect to update_subscription page if email and phone are not provided
    if ($_SERVER["REQUEST_METHOD"] != "POST" && !isset($_POST['email']) && !isset($_POST['phone'])) {
        header("Location: update_subscription.php");
        exit();
    }
}

// Retrieve email and phone from GET parameters if available
$email = isset($_GET['email']) ? $_GET['email'] : (isset($_POST['email']) ? $_POST['email'] : "");
$phone = isset($_GET['phone']) ? $_GET['phone'] : (isset($_POST['phone']) ? $_POST['phone'] : "");

// Fetch user details from the database
if ($_SERVER["REQUEST_METHOD"] == "POST" || ($email && $phone)) {
    // Get database connection
    $conn = get_connection();

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ? AND phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        // User found, fetch details
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $address = $row['address'];
        $emailNotifications = $row['emailNotifications'];
        $phoneNotifications = $row['phoneNotifications'];
        $streetOne = $row['streetOne'];
        $streetOneSuburb = $row['streetOneSuburb'];
        $streetTwo = $row['streetTwo'];
        $streetTwoSuburb = $row['streetTwoSuburb'];
        $streetThree = $row['streetThree'];
        $streetThreeSuburb = $row['streetThreeSuburb'];
    } else {
        // User not found, redirect to update_subscription page
        header("Location: update_subscription.php");
        exit();
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brisbane Road Closures - Update Subscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/form.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    // Function to fetch street options from the database
    function fetchStreets(input, optionsContainer) {
        $.ajax({
            url: 'fetch_streets.php',
            type: 'GET',
            data: { input: input },
            success: function(response) {
                optionsContainer.html(response);
            }
        });
    }

    // Autocomplete functionality for street search bars
    $('input[name="street1"], input[name="street2"], input[name="street3"]').keyup(function() {
        var input = $(this).val();
        var optionsContainerId = '#' + $(this).attr('id') + 'Options';
        var optionsContainer = $(optionsContainerId);
        
        if (input.length >= 2) {
            fetchStreets(input, optionsContainer);
            optionsContainer.show();
        } else {
            optionsContainer.hide();
        }
    });

    // Select street option from autocomplete
    $(document).on('click', '.autocomplete-option', function() {
        var optionValue = $(this).text();
        var inputId = $(this).parent().attr('id').replace('Options', '');
        $('#' + inputId).val(optionValue);
        $('#' + inputId + 'Options').hide();
    });

    

    // Hide autocomplete options on form click
    $('form').click(function() {
        $('.autocomplete-options').hide();
    });
});
</script>
    <style>
       .update-button,
.update-button-link {
    display: block; /* Make the <a> tag a block-level element to take up the entire width */
    text-align: center; /* Center-align the text within the <a> tag */
    font-size: 12px; /* Set the font size to match the button */
    line-height: 1.5; /* Adjust line height for better vertical alignment */
    background-color: #063652;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%; /* Adjusting width considering padding */
    margin-top: 10px;
    margin-right: 10px;
    margin-bottom: 10px;
    box-sizing: border-box; /* Include padding and border in the width */
    text-decoration: none; /* Added text-decoration: none; to remove underline from <a> tag */
}
    </style>
</head>
<body>

<header class="header">
    <!-- Wrapped only the logo image with an anchor tag -->
    <div class="logo">
      <a href="https://www.qld.gov.au">
        <!-- Added a class to the image -->
        <img class="logo-image" src="https://www.qld.gov.au/__data/assets/git_bridge/0029/95447/static.qgov.net.au/assets/v4/latest/images/coat-of-arms/qg-coa-white.svg" alt="Queensland Government Logo">
      </a>
      <H1 class="header-text">Brisbane Road Closures</H1>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Search Here...">
        <button type="button">Search</button>
    </div>
</header>

<nav class="navigation-bar">
    <a class="navButton" href="index.php" tabindex="1">Home</a>
    <a class="navButton" href="list.php" tabindex="2">Planned Closures</a>
    <a class="navButton" href="subscribe.php" tabindex="3">Signup for notifications</a>
    <a class="navButton" href="manage.php" tabindex="4">Manage Notifications</a>
    <a class="navButton" href="admin/index.php" tabindex="5">Admin Portal</a>
</nav>

<section class="boat-section">
    
    <form method="post" class="form" onsubmit="return validateForm()" action="update_process.php">
    <h2 class="form-heading">Unsubscribe</h2>
    <a href="remove_user.php?email=<?php echo $email; ?>&phone=<?php echo $phone; ?>" class="update-button">Remove Subscription</a>
    <h2 class="form-heading">Update Subscription</h2>
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="phone" value="<?php echo $phone; ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Name" value="<?php echo $name; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>" readonly required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Phone Number" value="<?php echo $phone; ?>" readonly required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Address" value="<?php echo $address; ?>" required>

        <div class="notification-type">
            <label>Notification Type:</label>
            <div class="checkbox-container">
                <input type="checkbox" id="emails" name="emailNotifications" class="notification-checkbox" <?php if ($emailNotifications) echo "checked"; ?>>
                <label for="emails" class="notification-label">Emails</label>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" id="text" name="phoneNotifications" class="notification-checkbox" <?php if ($phoneNotifications) echo "checked"; ?>>
                <label for="text" class="notification-label">Texts</label>
            </div>
        </div>

        <!-- Street Notification 1 -->
        <div class="street-notification">
        <label for="street1">Street Notification 1:</label>
        <input type="text" id="street1" name="street1" placeholder="Search for street" value="<?php echo ($streetOne && $streetOneSuburb) ? $streetOne . ', ' . $streetOneSuburb : ''; ?>" required>
        <div id="street1Options" class="autocomplete-options"></div>

        <!-- Street Notification 2 -->
        <label for="street2" style="margin-top: 5px;">Street Notification 2:</label>
        <input type="text" id="street2" name="street2" placeholder="Search for street" value="<?php echo ($streetTwo && $streetTwoSuburb) ? $streetTwo . ', ' . $streetTwoSuburb : ''; ?>">
        <div id="street2Options" class="autocomplete-options"></div>

        <!-- Street Notification 3 -->
        <label for="street3" style="margin-top: 5px;">Street Notification 3:</label>
        <input type="text" id="street3" name="street3" placeholder="Search for street" value="<?php echo ($streetThree && $streetThreeSuburb) ? $streetThree . ', ' . $streetThreeSuburb : ''; ?>">
        <div id="street3Options" class="autocomplete-options"></div>
        </div>


        <?php
            // Check if a success message is set in the session
            if (isset($_SESSION['success_message'])) {
                echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
                // Clear the success message from the session to avoid displaying it again
                unset($_SESSION['success_message']);
            }
        ?>

        <div class="form-buttons">
            <button type="submit" class="update-button">Update Subscription</button>

            
        </div>
    </form>
</section>

<footer>
    <div class="columns-container">
        <div class="left-column">
            <h3 class="footer-title">Cultural Acknowledgement</h3>
            <p>We pay our respects to the Aboriginal and Torres Strait Islander ancestors of this land, their spirits and their legacy. The foundations laid by these ancestors—our First Nations peoples—give strength, inspiration and courage to current and future generations towards creating a better Queensland.</p>
        </div>
        <div class="right-column">
            <p class="copyright">© The State of Queensland 2024</p>
        </div>
    </div>
    <div class="bottom-links">
        <a href="#">Help</a>
        <a href="https://www.qld.gov.au/legal/disclaimer">Disclaimer</a>
        <a href="#">Accessibility</a>
        <a href="#">Other Languages</a>
    </div>
</footer>

</body>
</html>
