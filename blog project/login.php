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

    // Retrieve username from POST request
    $username = $conn->real_escape_string($_POST['username']); // Sanitize input

    // Prepare SQL statement using prepared statement
    $sql = "SELECT * FROM Users WHERE Username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // User found, fetch user data
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Pass'];

        // Verify password
        $password = $_POST['password'];
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $row['Email'];

            // Check if the user is an admin
            if ($row['isAdmin'] == 1) {
                // Redirect admin to the admin account page
                header("Location: adminAccount.php");
                exit();
            } else {
                // Redirect regular user to the user account page
                header("Location: userAccount.php");
                exit();
            }
        } else {
            // Password is incorrect, redirect back to login page with error message
            header("Location: login.php?error=1");
            exit();
        }
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
    <link rel="stylesheet" href="CSS/Login-Style.css">
    <title>Login</title>
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
            <h2>Log In</h2>
            <!--redirect when you want to register-->
            <p>Don't have an account? <a href="signup.php">Register here</a>.</p>
            <form id="loginForm" onsubmit="return validateForm(event)">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <p style="text-align: right;"><a href="#" onclick="forgotPassword()">Forgot Password?</a></p>
                <button type="submit">Log In</button>
            </form>

        </div>
    </main>

    <script>
        // Validation and form submission logic for login
        function validateForm(event) {
            event.preventDefault();
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            // Send username and password to the login.php script using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "login.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("Login Successful!");
                        // Redirect to user page
                        window.location.href = "userPage.php";
                    } else {
                        console.log("Login Failed!");
                        alert("Invalid username or password. Please try again.");
                    }
                }
            };
            xhr.send("username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password));
        }
    </script>

</body>
    <script>
        // Refresh the page every 30 seconds
        setInterval(function () {
            location.reload();
        }, 30000);
    </script>

</html>