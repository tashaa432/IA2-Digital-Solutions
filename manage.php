<?php
session_start();

// Initialize error message variable
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

// Initialize form data
$email_value = isset($_POST['email']) ? $_POST['email'] : "";
$phone_value = isset($_POST['phone']) ? $_POST['phone'] : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user inputs from the form
    $email = $email_value;
    $phone = $phone_value;

    // Get database connection
    $conn = get_connection();

    // SQL query to check if the user exists
    $sql = "SELECT * FROM users WHERE email = ? AND phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a record exists
    if ($result->num_rows === 1) {
        // User exists, redirect to update page
        header("Location: update_subscription.php?email=$email&phone=$phone");
        exit();
    } else {
        // No matching record found
        $error_message = "No subscription found with the provided email and phone number.";
    }

    // Close prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brisbane Road Closures - Update</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/form.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
        });
    </script>
</head>
<body>

  <!-- Header -->
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
  <!-- Navigation Bar -->
  <nav class="navigation-bar">
    <a class="navButton" href="index.php" tabindex="1">Home</a>
    <a class="navButton" href="list.php" tabindex="2">Planned Closures</a>
    <a class="navButton" href="subscribe.php" tabindex="3">Signup for notifications</a>
    <a class="navButton" href="manage.php" tabindex="4">Manage Notifications</a>
    <a class="navButton" href="admin/index.php" tabindex="5">Admin Portal</a>
  </nav>

  <!-- Main Content -->
  <main>
      <h2 class="form-heading">Manage Road Closure Notifications</h2>
      <section class="boat-section">
          <form method="post" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <label for="email">Email:</label><br>
              <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email_value; ?>" required><br>
              <label for="phone">Phone Number:</label><br>
              <input type="tel" id="phone" name="phone" placeholder="Phone Number" value="<?php echo $phone_value; ?>" required><br><br>
              <div class="phone-error" style="display: none; color: red;">Phone number must be 10 digits</div>
              <?php if (!empty($error_message)) : ?>
                  <p><?php echo $error_message; ?></p>
              <?php endif; ?>
              <?php if (empty($success_message)) : ?>

                <?php
                    // Display success message if available
                    if (isset($_SESSION['success_message'])) {
                        echo '<div>' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']);
                    }

                    // Display error message if available
                    if (isset($_SESSION['error_message'])) {
                        echo '<div>' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']);
                    }
                ?>
              <button type="submit">Submit</button>
          </form>
          <?php else: ?>
              <p><?php echo $success_message; ?></p>
              <!-- Display the form again with empty values -->
              <form method="post" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                  <label for="email">Email:</label><br>
                  <input type="email" id="email" name="email" placeholder="Email" value="" required><br>
                  <label for="phone">Phone Number:</label><br>
                  <input type="tel" id="phone" name="phone" placeholder="Phone Number" value="" required><br><br>
                  <div class="phone-error" style="display: none; color: red;">Phone number must be 10 digits</div>
                  <button type="submit">Submit</button>
              </form>
          <?php endif; ?>
      </section>
  </main>

  <!-- Footer -->
  <footer>
      <div class="columns-container">
          <!-- Left column: Heading and Short Paragraph -->
          <div class="left-column">
              <h3 class="footer-title">Cultural Acknowledgement</h3>
              <p>We pay our respects to the Aboriginal and Torres Strait Islander ancestors of this land, their spirits and their legacy. The foundations laid by these ancestors—our First Nations peoples—give strength, inspiration and courage to current and future generations towards creating a better Queensland.</p>
          </div>

          <!-- Right column: Copyright notice -->
          <div class="right-column">
              <p class="copyright">© The State of Queensland 2024</p>
          </div>
      </div>

      <!-- Bottom links spread evenly -->
      <div class="bottom-links">
          <a href="#">Help</a>
          <a href="https://www.qld.gov.au/legal/disclaimer">Disclaimer</a>
          <a href="#">Accessibility</a>
          <a href="#">Other Languages</a>
      </div>
  </footer>

</body>
</html>
