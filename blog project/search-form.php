<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/Style.css">
    <link rel="stylesheet" href="CSS/loading.css">
    <script src="loading.js"></script>
    <title>Search Form</title>
</head>

<body>
    <div class="loader"></div>
    <header>
        <h1>Tumblify</h1>
        <nav>
            <ul>
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li class="currentPage"><a href="search-form.php">Search</a></li>
                <?php
                    session_start();
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                        // If user is logged in
                        echo "<li class='user-icon-container'><a href='userPage.php'><span class='user-icon' style='color: white;'>&#x1F47B;</span> " . $_SESSION['username'] . "</a></li>";
                    } else {
                        // If user is not logged in
                        echo "<li><a href='login.php'>Log In</a></li>";
                    }
                ?>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <!--function to send back to home page when X clicked on search-->
    <main>
        <form action="search-results.php" method="get">
            <label for="searchQuery">Search:</label>
            <input type="text" id="searchQuery" name="search" required>
            <button type="submit" onclick="goToResults()">Search</button>
            <button type="button" onclick="goBackToHome()">X</button>
        </form>
    </main>
    <script>
        function goBackToHome() {
            window.location.href = "Trending.php";
        }
        function goToResults() {
            window.location.href = "search-results.php";
        }
    </script>
</body>
    <script>
        // Refresh the page every 30 seconds
        setInterval(function () {
            location.reload();
        }, 30000);
    </script>
</html>