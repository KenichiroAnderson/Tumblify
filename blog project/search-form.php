<?php
session_start(); // Start session

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
    <title>Search Form</title>
</head>

<body>
    <header>
        <h1>Tumblify</h1>
        <nav>
            <ul>
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li><a href="search-form.html">Search</a></li>
                <?php
                session_start();
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    // If user is logged in, display their username next to an icon
                    echo "<li><a href='userPage.php'><img src='images/user-icon.png' alt='User Icon'> " . $_SESSION['username'] . "</a></li>";
                } else {
                    // If user is not logged in, display the login link
                    echo "<li><a href='login.php'>Log In</a></li>";
                }
                ?>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <!--function to send back to home page when X clicked on search-->
    <main>
        <form action="search-results.html" method="get">
            <label for="searchQuery">Search:</label>
            <input type="text" id="searchQuery" name="q" required>
            <button type="submit">Search</button>
            <button type="button" onclick="goBackToHome()">X</button>
        </form>
    </main>
    <script>
        function goBackToHome() {
            window.location.href = "Trending.php";
        }
    </script>
</body>

</html>