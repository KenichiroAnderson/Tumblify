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

    // Database connection
    $servername = "localhost";
    $db_username = "60531845";
    $db_password = "60531845";
    $dbname = "db_60531845";

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve profile picture path from the database
    $profilePicture = '';

    $sql = "SELECT ProfilePicture FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found in database, fetch profile picture path
        $row = $result->fetch_assoc();
        $profilePicture = "images/" . $row['ProfilePicture'];
    } else {
        // User not found or profile picture path not available, use default image path
        $profilePicture = "images/profile_pictures/" . $username . ".jpg"; // Default image path
        //$profilePicture = "images/profile_pictures/Danny.jpg";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/userPage.css">
    <link rel="stylesheet" href="CSS/loading.css">
    <script src="loading.js"></script>
    <title>User Account - Blog Post</title>
</head>

<body>
    <div class="loader"></div>
    <header>
        <div>
        <img src="images/tumblifyIcon.png" alt="Icon" class= "logo">
        <h1>Tumblify</h1>
        </div>
        <nav>
            <ul>
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li><a href="search-form.php">Search</a></li>
                <li class="user-icon-container">
                    <a href="userPage.php">
                        <?php echo $username; ?><br>
                        <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture">
                    </a>
                </li>
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
            <?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true): ?>
                <button onclick="redirectToAdminAccount()">Go to Admin Dashboard</button>
            <?php endif; ?>
        </div>

        <div class="rightbox">
            <h2>My Profile</h2>
            <a href="addPost.php"><button>Add New Post</button></a>
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

        <div class="profile-picture-container">
            <img src="<?php echo $profilePicture; ?>" alt="Profile Picture">
        </div>
    </div>

    <script>

        function redirectToAdminAccount() {
            window.location.href = "adminAccount.php";
        }

        // Refresh the page every 30 seconds
        setInterval(function () {
            location.reload();
        }, 30000);
    </script>
</body>

</html>
