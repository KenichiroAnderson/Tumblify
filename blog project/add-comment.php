<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "error"; // User is not logged in
    exit();
}

// Check if all required data is provided
if (!isset($_POST['postID']) || !isset($_POST['commentText'])) {
    echo "error"; // Data is not provided
    exit();
}

// Sanitize inputs
$postID = $_POST['postID'];
$commentText = htmlspecialchars($_POST['commentText']); // Sanitize comment text

// Database connection
    $servername = "localhost";
    $username = "60531845";
    $password = "60531845";
    $dbname = "db_60531845";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "error"; // Connection failed
    exit();
}

// Insert comment into the database
$sql = "INSERT INTO Comments (PostID, UserID, CommentText) VALUES ('$postID', '{$_SESSION['userID']}', '$commentText')";

if ($conn->query($sql) === TRUE) {
    echo "success"; // Comment added successfully
} else {
    echo "error"; // Error adding comment
}

// Close connection
$conn->close();
?>