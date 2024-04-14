<?php
session_start();

if (!isset($_SESSION['_id'])) {
    // User is not logged in, redirect to login page
    header("Location: index.html");
    exit();
}

// User is logged in, continue to display the dashboard content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brisbane Road Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="../Styles/form.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

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
      <H1 class="header-text">Brisbane Road Closures Admin</H1>
    </div>
    </div>
    <!-- Logout button -->
    <div class="logout-button">
        <a href="logout.php" class="logout-button" style="text-decoration:none">Logout</a>
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
    <h2 class="form-heading">Upload CSV Data</h2>
    <form action="upload.php" class="form" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <label for="csvFile">Select CSV file:</label>
        <input type="file" name="csvFile" id="csvFile">
        <p style="margin-right: 10px;">The uploaded CSV file contains accurate data, and it is laid out in the correct format.</p>
        <div class="declaration" style="display: flex; align-items: center;">
            <input type="checkbox" name="agreement" id="agreement" class="notification-checkbox"> 
            <label class="notification-label" style="margin-left: 5px;">I agree</label>
        </div>
        
        <?php
        // Check for success or error messages and display them
        if(isset($_SESSION['success_message'])) {
            echo "<p>{$_SESSION['success_message']}</p>";
            unset($_SESSION['success_message']); // Clear the success message
        } elseif(isset($_SESSION['error_message'])) {
            echo "<p>{$_SESSION['error_message']}</p>";
            unset($_SESSION['error_message']); // Clear the error message
        }
        ?>


        <button type="submit" name="submit">Upload CSV</button>
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
