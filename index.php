<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brisbane Road Closures</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/styles.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="styles/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
      .bg-image {
        position: relative; /* Added position relative */
        background-image: url('Images/bg.jpeg');
        background-size: cover;
        background-position: center;
      }
      .attribution-box {
        position: absolute;
        bottom: 0; /* Set bottom to 0 */
        right: 0; /* Set right to 0 */
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent grey */
        color: white;
        padding: 5px 10px;
        font-size: 12px;
        margin: 0; /* Remove margin */
        z-index: 1; /* Ensure it appears above other content */
      }
      .attribution-box a {
        color: white; /* Set link color to white */
        text-decoration: underline; /* Underline the links */
      }
    </style>
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
  <main class="bg-image">
    <div class="floating-box">
        <h2>Planned Road Closures</h2>
        <p>See an up to date list that contains temporary road closures across Brisbane City Council.</p>
        <a href="list.php" class="read-more-btn" tabindex="6">Read More</a>
      </div>
      <div class="attribution-box"><a href="https://commons.wikimedia.org/wiki/File:Traffic_congestion_at_Riverside_Expressway,_Brisbane,_Queensland,_2021,_02.jpg">Kgbo</a>, <a href="https://creativecommons.org/licenses/by-sa/4.0">CC BY-SA 4.0</a>, via Wikimedia Commons</div> <!-- Added attribution box -->
</main>
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
