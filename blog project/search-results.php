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
    <link rel="stylesheet" href="CSS/Trending-Style.css">
    <link rel="stylesheet" href="CSS/loading.css">
    <script src="loading.js"></script>
    <title>Nothing Found</title>
</head>

<body>
    <div class="loader"></div>
    <header>
        <h1>Tumblify</h1>
        <nav>
            <ul>
                <!-- always update these when you make a new header, do for all pages-->
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li class = "currentPage"><a href="search-form.php">Search</a></li>
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
        <?php
        // Database connection
        $servername = "localhost";
        $username = "60531845";
        $password = "60531845";
        $dbname = "db_60531845";
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the user is logged in
        $loggedin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

        // Get the search query from the URL parameter
        $searchQuery = $_GET['search'];

        // Perform the search query in the database
        $sql = "SELECT Posts.*, Users.Username FROM Posts INNER JOIN Users ON Posts.UserID = Users.UserID WHERE Posts.Title LIKE '%$searchQuery%' OR Posts.Text LIKE '%$searchQuery%' OR Users.Username LIKE '%$searchQuery%'";
        $result = $conn->query($sql);

        // Check if there are any posts
        if ($result->num_rows === 0) {
            echo "<p>No posts found.</p>";
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "<h2>Search results:</h2>";
                // Output post content
                echo "<article>";
                echo "<h1>";
                echo "<div><a rel='author'>" . $row["Username"] . "</a>";

                // Check if user is logged in
                if ($loggedin) {
                    echo "<button class='follow'>Follow</button>";
                } else {
                    // If user is not logged in
                    echo "<button class='follow' disabled>Follow</button>";
                }

                echo "</div>";
                echo "</h1>";
                echo "<div>";
                echo "<figure><img src='" . $row["ImageURL"] . "' alt='Post Image'></figure>";
                echo "<p>" . $row["Text"] . "</p>";
                echo "</div>";
                if ($loggedin) {
                    echo "<button class='comment-button' onclick='openCommentsPopup(" . $row["PostID"] . ")'>View/Add Comments</button>";
                } else {

                    echo "<button class='comment-button' disabled>View/Add Comments</button>";
                }
                echo "<div id='commentsContainer_" . $row["PostID"] . "' class='comments-container'></div>"; // Container for comments
                echo "</article>";
            }
        }

        $conn->close();
        ?>

        <div id="commentsPopup" class="comments-popup" style="display: none;">
            <div class="comments-popup-content">
                <span class="close" onclick="closeCommentsPopup()">&times;</span>
                <!-- Close button X -->
                <h2>Comments</h2>
                <div id="commentsContainer"></div>
                <form id="commentForm" class="comment-form" <?php if (!$loggedin) echo "style='display: none;'"; ?> onsubmit="return addComment()">
                    <input type="hidden" id="postID" name="postID" value="">
                    <textarea id="commentText" name="commentText" placeholder="Write a comment..." required></textarea>
                    <button type="submit">Add Comment</button>
                </form>
                <?php if (!$loggedin) echo "<p>Please log in to add comments.</p>"; ?>
            </div>
        </div>
    </main>
    <script>
        // Function to close comments popup
        function closeCommentsPopup() {
            document.getElementById('commentsPopup').style.display = 'none';
        }
        
        // Function to open comments popup
        function openCommentsPopup(postID) {
            document.getElementById('commentsPopup').style.display = 'block';
            fetchComments(postID);
            document.getElementById('postID').value = postID;
        }

        // Function to fetch comments for a post
        function fetchComments(postID, callback) {
            $.ajax({
                url: 'fetch-comments.php',
                type: 'GET',
                data: { postID: postID },
                success: function (data) {
                    $('#commentsContainer').html(data);
                    if (typeof callback === 'function') {
                        callback();
                    }
                },
                error: function () {
                    alert('Error fetching comments.');
                }
            });
        }


        // Function to add comment
        function addComment() {
            var postID = $('#postID').val(); // Get the postID from the hidden field
            var commentText = $('#commentText').val();
            $.ajax({
                url: 'add-comment.php',
                type: 'POST',
                data: {
                    postID: postID,
                    commentText: commentText
                },
                success: function (response) {
                    if (response === 'success') {
                        fetchComments(postID); // Refresh comments after adding
                        $('#commentText').val(''); // Clear comment text area
                    } else {
                        alert('Error adding comment.');
                    }
                },
                error: function () {
                    alert('Error adding comment.');
                }
            });
            return false; // Prevent form submission
        }

        // Refresh the page every 30 seconds
        setInterval(function () {
            location.reload();
        }, 30000);
    </script>
</body>

</html>