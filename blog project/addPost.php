<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Database connection parameters
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

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $text = $_POST['text'];

    // File upload handling
    $targetDirectory = "Tumblify/blog project/images/"; // Directory to store uploaded images
    $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 5000000) { // 5MB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // If everything is ok, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Insert post into database
            $userId = $_SESSION['userId']; // Assuming you have stored the user ID in the session
            $imageUrl = $targetFile; // Image URL to be saved in the database
            $query = "INSERT INTO Posts (UserID, Title, ImageURL, Text) VALUES ('$userId', '$title', '$imageUrl', '$text')";
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo "Post created successfully!";
            } else {
                echo "Error creating post: " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <link rel="stylesheet" href="CSS/Style.css">
</head>
<body>
    <header>
        <h1>Add Post</h1>
        <nav>
            <ul>
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li><a href="search-form.php">Search</a></li>
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    // If user is logged in
                    echo "<li class='user-icon-container'><a href='userPage.php'><span class='user-icon' style='color: white;'>&#x1F47B;</span> " . $_SESSION['username'] . "</a></li>";
                    echo "<li><a href='logout.php'>Log Out</a></li>";
                } else {
                    // If user is not logged in
                    echo "<li><a href='login.php'>Log In</a></li>";
                    echo "<li><a href='signup.php'>Sign Up</a></li>";
                }
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Create a New Post</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <label for="title">Title:</label><br>
                <input type="text" id="title" name="title" required><br>
                <label for="image">Image:</label><br>
                <input type="file" id="image" name="image" accept="image/*" required><br>
                <label for="text">Text:</label><br>
                <textarea id="text" name="text" rows="4" required></textarea><br>
                <button type="submit" name="submit">Submit Post</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Tumblify</p>
    </footer>
</body>
</html>
