<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['username']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] == 1) {
    // If not logged in or not an admin, redirect to login page
    header("Location: login.php");
    exit();
}

// If logged in as admin, display admin-specific content below
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account</title>
    <link rel="stylesheet" href="styles.css"> <!-- Adjust the path to your CSS file -->
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?> (Admin)</h1>
        <nav>
            <ul>
                <li><a href="adminAccount.php">Admin Dashboard</a></li>
                <li><a href="userManagement.php">User Management</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Admin Dashboard</h2>
            <!-- Admin-specific content goes here -->
            <p>This is the admin dashboard. You can manage users, view statistics, etc. </p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Your Website</p>
    </footer>
</body>
</html>
