<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brisbane Road Closures - List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/list.css">
    <link rel="stylesheet" href="styles/form.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
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

  <!-- Navigation Bar -->
  <nav class="navigation-bar">
    <a class="navButton" href="index.php">Home</a>
    <a class="navButton" href="list.php">Planned Closures</a>
    <a class="navButton" href="subscribe.php">Signup for notifications</a>
    <a class="navButton" href="manage.php">Manage Notifications</a>
    <a class="navButton" href="admin/index.php">Admin Portal</a>
</nav>

  <!-- Main Content -->
  <div class="container">
    <h2 class="heading">Temporary Road Closures</h2>
    <div class="suburb-search-bar">
    <form action="" method="GET">
    <input type="text" placeholder="Search by Suburb" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>
    </div>
    <table>
    <?php
    // Connect to the database
    $servername = "localhost"; // Change this to your database server name if different
    $username = "root";
    $password = "_fIpGeMVe[(.sRtb";
    $database = "bneTraffic";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select data
    $sql = "SELECT DATE_FORMAT(startDate, '%d-%m-%Y') AS startDate, DATE_FORMAT(endDate, '%d-%m-%Y') AS endDate, roadPrimary, suburb, closureType, TIME_FORMAT(startTime, '%H:%i') AS startTime, TIME_FORMAT(endTime, '%H:%i') AS endTime FROM Traffic";

    // Check if search query is set
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        // Modify SQL query to filter results based on suburb
        $sql .= " WHERE LOWER(suburb) LIKE LOWER('%$search%')";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<tr><th>Start Date</th><th>End Date</th><th>Road Primary</th><th>Suburb</th><th>Closure Type</th><th>Start Time</th><th>End Time</th></tr>";
        while($row = $result->fetch_assoc()) {
            $roadPrimary = ucfirst(strtolower($row["roadPrimary"]));
            $suburb = ucfirst(strtolower($row["suburb"]));
            echo "<tr><td>".$row["startDate"]."</td><td>".$row["endDate"]."</td><td>".$roadPrimary."</td><td>".$suburb."</td><td>".$row["closureType"]."</td><td>".$row["startTime"]."</td><td>".$row["endTime"]."</td></tr>";
        }
    } else {
        echo "<tr><td colspan='7'>0 results</td></tr>";
    }
    $conn->close();
    ?>
    </table>
</div>
  
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
        <p class="copyright">© 2024 The State of Queensland 2024</p>
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
