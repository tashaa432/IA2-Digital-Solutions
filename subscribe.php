<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Prepare data for insertion
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $phoneNotifications = isset($_POST['phoneNotifications']) ? 1 : 0;
    $emailNotifications = isset($_POST['emailNotifications']) ? 1 : 0;

    // Extract street and suburb for each notification street
    $streetOneParts = explode(", ", $_POST['street1']);
    $streetTwoParts = explode(", ", $_POST['street2']);
    $streetThreeParts = explode(", ", $_POST['street3']);

    $streetOne = $streetOneParts[0];
    $streetOneSuburb = $streetOneParts[1];
    $streetTwo = $streetTwoParts[0];
    $streetTwoSuburb = $streetTwoParts[1];
    $streetThree = $streetThreeParts[0];
    $streetThreeSuburb = $streetThreeParts[1];

    // Prepare SQL statement
    $sql = "INSERT INTO Users (name, email, phone, address, phoneNotifications, emailNotifications, streetOne, streetOneSuburb, streetTwo, streetTwoSuburb, streetThree, streetThreeSuburb)
    VALUES ('$name', '$email', '$phone', '$address', $phoneNotifications, $emailNotifications, '$streetOne', '$streetOneSuburb', '$streetTwo', '$streetTwoSuburb', '$streetThree', '$streetThreeSuburb')";

    if ($conn->query($sql) === TRUE) {
        // Success message
        $success_message = "You have been successfully subscribed!";
    } else {
        // Error message
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brisbane Road Closures - Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/form.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function validateForm() {
        var agreement = document.getElementById("agreement");
        if (!agreement.checked) {
            alert("Please agree to the terms to proceed.");
            return false;
        }
        return true;
    }
    </script>
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

    // Form submission validation
    $('form').submit(function(event) {
        var phoneNumber = $('#phone').val();
        // Check if phone number is exactly 10 digits
        if (phoneNumber.length !== 10) {
            // Prevent form submission
            event.preventDefault();
            // Show error message
            $('.phone-error').show();
        }
    });

    // Hide autocomplete options on form click
    $('form').click(function() {
        $('.autocomplete-options').hide();
    });
});
</script>
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
    <h2 class="form-heading">Subscribe to Road Closure Notifications</h2>
    <form method="post" class="form" onsubmit="return validateForm()">

    <?php if(isset($success_message)): ?>
          <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if(isset($error_message)): ?>
          <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        </br>

        <label for="name">Name*:</label>
        <input type="text" id="name" name="name" placeholder="Name" required>

        <label for="email">Email*:</label>
        <input type="email" id="email" name="email" placeholder="Email" required>

        <label for="phone">Phone Number*:</label>
        <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>

        <label for="address">Address*:</label>
        <input type="text" id="address" name="address" placeholder="Address (Won't be used for notifications)" required>

        <div class="notification-type">
            <label>Notification Type*:</label>
            <div class="checkbox-container">
                <input type="checkbox" id="emails" name="emailNotifications" class="notification-checkbox">
                <label for="emails" class="notification-label">Emails</label>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" id="text" name="phoneNotifications" class="notification-checkbox">
                <label for="text" class="notification-label">Texts</label>
            </div>
        </div>

        <!-- Street Notification 1 -->
        <div class="street-notification">
            <label for="street1">Street Notification 1*:</label>
            <input type="text" id="street1" name="street1" placeholder="Search for street" required>
            <div id="street1Options" class="autocomplete-options"></div>

            <!-- Street Notification 2 -->
            <label for="street2" style="margin-top: 5px;">Street Notification 2:</label>
            <input type="text" id="street2" name="street2" placeholder="Search for street">
            <div id="street2Options" class="autocomplete-options"></div>

            <!-- Street Notification 3 -->
            <label for="street3" style="margin-top: 5px;">Street Notification 3:</label>
            <input type="text" id="street3" name="street3" placeholder="Search for street">
            <div id="street3Options" class="autocomplete-options"></div>
        </div>
        <p style="margin-right: 10px;"></p>
        <div class="declaration" style="display: flex; align-items: center;">
            <input type="checkbox" name="agreement" id="agreement" class="notification-checkbox"> 
            <label class="notification-label" style="margin-left: 5px;">I agree to the Terms & Conditions</label>
        </div>
        <div class="phone-error" style="display: none; color: red;">Phone number must be 10 digits</div>
        <!-- Submit button -->
        <button type="submit">Submit</button>
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
