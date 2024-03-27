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
    <title>Nothing Found</title>
</head>

<body>
    <header>
        <h1>Tumblify</h1>
        <nav>
            <ul>
                <!-- always update these when you make a new header, do for all pages-->
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li><a href="search-form.php">Search</a></li>
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

    <main>
        <!-- simple thwrow message-->
        <p>Sorry, nothing can be found.</p>
    </main>
</body>
    <script>
        // Refresh the page every 30 seconds
        setInterval(function () {
            location.reload();
        }, 30000);
    </script>

</html>