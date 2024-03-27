<?php
// Check if postID is provided in the request
if(isset($_GET['postID'])) {
    // Database connection
    $servername = "localhost";
    $username = "60531845";
    $password = "60531845";
    $dbname = "db_60531845";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $postID = $conn->real_escape_string($_GET['postID']);

    $sql = "SELECT c.CommentID, c.CommentText, c.CommentDate, u.Username 
            FROM Comments c 
            INNER JOIN Users u ON c.UserID = u.UserID 
            WHERE c.PostID = '$postID'";
    $result = $conn->query($sql);

    // Check if there are any comments
    if ($result->num_rows > 0) {
        // Output data of each comment
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<p><strong>Username:</strong> " . $row['Username'] . "</p>";
            echo "<p><strong>Comment:</strong> " . $row['CommentText'] . "</p>";
            echo "<p><strong>Date:</strong> " . $row['CommentDate'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No comments available for this post.";
    }

    $conn->close();
} else {
    // If postID is not provided:
    echo "Error: postID parameter is missing.";
}
?>

