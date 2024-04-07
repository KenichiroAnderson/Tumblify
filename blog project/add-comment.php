<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "User is not logged in";
    exit();
}

// Check if all required data is provided
if (!isset($_POST['postID']) || !isset($_POST['commentText'])) {
    echo "Missing data";
    exit();
}

// Validate postID
$postID = $_POST['postID'];
if (!is_numeric($postID)) {
    echo "Invalid post ID";
    exit();
}

// Sanitize and validate commentText
$commentText = trim($_POST['commentText']);
if (empty($commentText)) {
    echo "Comment text is empty";
    exit();
}
$commentText = htmlspecialchars($commentText); // Sanitize comment text

// Database connection parameters
$servername = "localhost";
$username = "60531845";
$password = "60531845";
$dbname = "db_60531845";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    exit();
}

// Prepare and bind SQL statement with prepared statement
$sql = "INSERT INTO Comments (PostID, UserID, CommentText) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $postID, $_SESSION['userID'], $commentText);

// Execute SQL statement
if ($stmt->execute()) {
    echo "success"; // Comment added successfully
} else {
    echo "Error adding comment: " . $conn->error; // Error adding comment
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
