<?php
session_start(); // Start session

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Retrieve user information from session
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$profilePicture = $_SESSION['profilePicture']; // Retrieve profile picture path

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/userPage.css">
    <title>User Account - Blog Post</title>
</head>

<body>
    <header>
        <h1>Tumblify</h1>
        <nav>
            <ul>
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li><a href="search-form.php">Search</a></li>
                <li class="user-icon-container"><a href="userPage.php"><img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture">&#x1F47B;</a> <?php echo $username; ?></li>
                <li><a href="logout.php">Log Out</a></li> <!-- Updated logout link -->
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="leftbox">
            <h2>Menu</h2>
            <ul>
                <li><a href="#">My Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Change Password</a></li>
            </ul>
        </div>

        <div class="leftbox">
            <h2>My Profile</h2>
            <form action="#" method="post">
                <div>
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" class="input" value="<?php echo $username; ?>" readonly>
                </div>
                <div>
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" class="input" value="<?php echo $email; ?>" readonly>
                </div>
                <div>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" class="input" value="<?php echo $password; ?>" readonly>
                </div>
                <!-- Button for refreshing the page -->
                <button type="button" class="btn" onclick="location.reload()">Refresh</button>
            </form>
        </div>
    </div>

    <script>
        // Refresh the page every 30 seconds
        setInterval(function () {
            location.reload();
        }, 30000);
    </script>
</body>

</html>
