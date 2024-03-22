<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username_db = "60531845";
    $password_db = "60531845";
    $dbname = "db_60531845";

    // Create connection
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user input from POST request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Hash the password - do this if you haven't implemented yet
    $confirmpassword = $_POST['confirmPassword'];

    // Prepare and bind SQL statement to insert user data into the database
    $sql = "INSERT INTO Users (Username, Email, Pass, Confirmpassword) VALUES ('$username', '$email', '$password', '$confirmpassword')";
   // $stmt = $conn->prepare($sql);
    //$stmt->bind_param("ssss", $username, $email, $password, $confirmpassword);

    // Execute the statement
    /*
    if ($stmt->execute()) {
        // Account successfully created, redirect to login page
        header("Location: login.php");
        exit();
    } else {
        // Error occurred, redirect back to signup page with error message
        header("Location: signup.php?error=1");
        exit();
    }
    */

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
            <ul>
                <!-- always update these when you make a new header, do for all pages-->
                <li><a href="Trending.php">Trending Blogs</a></li>
                <li><a href="search-form.php">Search</a></li>
                <li><a href="login.php">Log In</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <!-- onsubmit means when the form is complete we send the data to validateForm method-->
            <form id="signupForm" action="#" method="post" onsubmit="return validateForm()">
                <h2>Ticket Tech Registration</h2>
                <!-- link to login if you have an account-->
                <p>Already have an account? <a href="login.php">Log in here</a>.</p>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <span id="usernameError" class="error-message"></span>

                <!-- E-mail -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <span id="emailError" class="error-message"></span>
                <!-- Password, 1st box -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span id="passwordError" class="error-message"></span>
                <!-- Password, 2nd box -->
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <span id="confirmPasswordError" class="error-message"></span>

                <button type="submit">Sign Up</button>
            </form>
            <div id="successMessage">Your account has been successfully validated! Page Will Redirect Within 20 Seconds.</div>
        </div>
    </main>

    <script>
        function validateForm() {
            event.preventDefault();
            /*Validation for sign up Simple getters*/
            var username = document.getElementById("username").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            /*Error thower*/
            var usernameError = document.getElementById("usernameError");
            var emailError = document.getElementById("emailError");
            var passwordError = document.getElementById("passwordError");
            var confirmPasswordError = document.getElementById("confirmPasswordError");

            usernameError.innerHTML = "";
            emailError.innerHTML = "";
            passwordError.innerHTML = "";
            confirmPasswordError.innerHTML = "";

            var isValid = true;

            // Username Error Msg / checker
            if (username.trim() === "") {
                usernameError.innerHTML = "Username is required!";
                isValid = false;
            }
            // Email Error Msg / checker
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailError.innerHTML = "Enter a valid email!";
                isValid = false;
            }
            // Password Error Msg / checker
            if (password.length < 8) {
                passwordError.innerHTML = "Password must be at least 8 characters long!";
                isValid = false;
            }
            // Ensure password and confirm are the same
            if (confirmPassword !== password) {
                confirmPasswordError.innerHTML = "Passwords do not match!";
                isValid = false;
            }
            // User feedback for account validation
            if (isValid) {
                // Print user data to console
                // **!!! get rid of before final submission or comment it out as this is bad for security !!!**
                console.log("User Data:", {
                    username,
                    email,
                    password
                });

                showSuccessMessage();
                return isValid;
            }
        }

        // Function to display success message and redirect to login page
        function showSuccessMessage() {
            var successMessage = document.getElementById("successMessage");
            successMessage.style.display = "block";
            setTimeout(function () {
                successMessage.style.display = "none";

                // Redirect to the login page
                console.log("Redirecting to login page");
                window.location.href = "login.php";
            }, 2000);
        }
    </script>
</body>

</html>

