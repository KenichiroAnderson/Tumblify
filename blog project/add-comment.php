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
    $commentText = $_POST['commentText'];

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

    // Prepare statement to insert comment into the database
    $stmt = $conn->prepare("INSERT INTO Comments (PostID, UserID, CommentText) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $postID, $_SESSION['userID'], $commentText);

    // Execute the statement
    if ($stmt->execute()) {
        echo "success"; // Comment added successfully
    } else {
        echo "error"; // Error adding comment
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
?>
