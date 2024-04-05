<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    // If not logged in or not an admin, redirect to login page
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $usernameDB = "60531845"; 
    $passwordDB = "60531845";
    $dbname = "db_60531845";
    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($_POST['action']) && !empty($_POST['action'])) {
        $action = $_POST['action'];
        
        switch($action) {
            case 'editPost':
                // editing posts
                $postId = $_POST['postId'];
                $newTitle = $_POST['newTitle'];
                $newContent = $_POST['newContent'];
                $query = "UPDATE Posts SET ColumnTitle = '$newTitle', ColumnText = '$newContent' WHERE ColumnPostID = $postId";
                $result = mysqli_query($conn, $query);
                
                if($result) {
                    echo "Post edited successfully!";
                } else {
                    echo "Error editing post: " . mysqli_error($conn);
                }
                break;
            case 'deletePost':
                //delete post query
                $postId = $_POST['postId'];
                $query = "DELETE FROM Posts WHERE ColumnPostID = $postId";
                $result = mysqli_query($conn, $query);
                
                if($result) {
                    echo "Post deleted successfully!";
                } else {
                    echo "Error deleting post: " . mysqli_error($conn);
                }
                break;
            case 'deleteUser':
                //deleting user query
                $userId = $_POST['userId'];
                $query = "DELETE FROM Users WHERE ColumnUserID = $userId";
                $result = mysqli_query($conn, $query);
                
                if($result) {
                    echo "User deleted successfully!";
                } else {
                    echo "Error deleting user: " . mysqli_error($conn);
                }
                break;
            default:
                // Invalid action
                break;
        }
    }
}

    // Fetch all table names from the database
    $tablesQuery = "SHOW TABLES";
    $tablesResult = mysqli_query($conn, $tablesQuery);
    $tables = mysqli_fetch_all($tablesResult);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderation</title>
    <link rel="stylesheet" href="CSS/Style.css">
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
            <h2>Moderation</h2>
            <form method="post">
                <input type="hidden" name="action" value="editPost">
                <input type="text" name="postId" placeholder="Post ID">
                <input type="text" name="newTitle" placeholder="New Title">
                <textarea name="newContent" placeholder="New Content"></textarea>
                <button type="submit">Edit Post</button>
            </form>
            <form method="post">
                <input type="hidden" name="action" value="deletePost">
                <input type="text" name="postId" placeholder="Post ID">
                <button type="submit">Delete Post</button>
            </form>
            <form method="post">
                <input type="hidden" name="action" value="deleteUser">
                <input type="text" name="userId" placeholder="User ID">
                <button type="submit">Delete User</button>
            </form>
        </section>

        <section>
            <h2>Database Tables</h2>
            <ul>
                <?php
                // display all tables 
                foreach ($tables as $table) {
                    echo "<li>$table[0]</li>";
                }
                ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Your Website</p>
    </footer>
</body>
</html>
