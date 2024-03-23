<?php
// Check if postID and commentText are provided in the request
if(isset($_POST['postID']) && isset($_POST['commentText'])) {
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

    // Sanitize postID and commentText
    $postID = $conn->real_escape_string($_POST['postID']);
    $commentText = $conn->real_escape_string($_POST['commentText']);

    // Get the userID from the session (assuming it's stored there)
    session_start();
    $userID = $_SESSION['userID']; // Adjust this based on how you store userID in the session

    // Get the current date and time
    $commentDate = date("Y-m-d H:i:s");

    // SQL query to insert the new comment into the Comments table
    $sql = "INSERT INTO Comments (PostID, UserID, CommentText, CommentDate) VALUES ('$postID', '$userID', '$commentText', '$commentDate')";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // If postID or commentText are not provided in the request, return an error message
    echo "Error: postID or commentText parameter is missing.";
}
?>
