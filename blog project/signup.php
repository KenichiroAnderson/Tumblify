<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $usernameDB = "60531845"; 
    $passwordDB = "60531845";
    $dbname = "db_60531845";
    // Create connection
    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve username and password from POST request
    $username = $conn->real_escape_string($_POST['username']); // Sanitize input
    $password = $conn->real_escape_string($_POST['password']); // Sanitize input

    // Prepare SQL statement using prepared statement
    $sql = "SELECT * FROM Users WHERE Username=? AND Pass=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // User found, set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Fetch additional user information if needed
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['Email'];
        $_SESSION['password'] = $row['Pass'];

        // Redirect to user page
        header("Location: userPage.php");
        exit();
    } else {
        // User not found, redirect back to login page with error message
        header("Location: login.php?error=1");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/Signup-Style.css">
    <title>Sign Up</title>
</head>

<body>
    <header>
        <h1>Tumblify</h1>
        <nav>
            <!-- always update these when you make a new header, do for all pages-->
            <ul>
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li><a href="search-form.php">Search</a></li>
                <li><a href="login.php">Log In</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Sign Up</h2>
            <!--redirect when you want to register-->
            <p>Already have an account? <a href="login.php">Log In here</a>.</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>

                <button type="submit" class="btn">Sign Up</button>
            </form>
        </div>
    </main>

    <script>
        // Validation logic for the signup form
        function validateForm() {
            var username = document.getElementById("username").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            // Username validation
            if (username.trim() === "") {
                alert("Username is required!");
                return false;
            }

            // Email validation
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Enter a valid email address!");
                return false;
            }

            // Password validation
            if (password.length < 8) {
                alert("Password must be at least 8 characters long!");
                return false;
            }

            // Password and Confirm Password match
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }

            return true;
        }
    </script>

</body>

</html>
