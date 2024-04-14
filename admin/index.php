<?php
$servername = "localhost";
$username = "root";
$password = "_fIpGeMVe[(.sRtb";
$database = "bneTraffic";
$error_message = ""; // Initialize error message variable

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to retrieve user's hashed password
    $sql = "SELECT _id, password FROM admins WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check Result
    if ($result->num_rows > 0) {
        // User found, check password
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            // Passwords match, login successful
            // Start session and store user ID
            session_start();
            $_SESSION['_id'] = $row['_id'];
            // Redirect to dashboard or another page
            header("Location: dashboard.php");
            exit();
        } else {
            // Passwords do not match
            $error_message = "Invalid email or password";
        }
    } else {
        // User not found
        $error_message = "Invalid email or password";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brisbane Roads Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="../Styles/form.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
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

  <nav class="navigation-bar">
    <a class="navButton" href="../index.php" tabindex="1">Home</a>
    <a class="navButton" href="../list.php" tabindex="2">Planned Closures</a>
    <a class="navButton" href="../subscribe.php" tabindex="3">Signup for notifications</a>
    <a class="navButton" href="../manage.php" tabindex="4">Manage Notifications</a>
    <a class="navButton" href="admin/index.php" tabindex="5">Admin Portal</a>
  </nav>
  <!-- Main Content -->
  <main class="boat-section">
        <h2 class="form-heading">Login</h2>
        <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <!-- Display error message here -->
            <?php if (!empty($error_message)): ?>
                <p><?php echo $error_message; ?></p>
            <?php endif; ?>
            <button type="submit">Login</button>
        </form>
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
