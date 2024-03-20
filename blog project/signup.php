<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set and not empty
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword']) &&
        !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])) {

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

        // Retrieve form data and sanitize inputs
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

        // Check if password matches confirm password
        if ($password !== $confirmPassword) {
            echo "Passwords do not match!";
            exit;
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert user data using prepared statements
        $sql = "INSERT INTO Users (Username, Email, Password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "User registered successfully!";
            // Redirect to login page
            header("Location: login.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "All fields are required!";
    }
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
                <li><a href="search-form.html">Search</a></li>
                <li><a href="login.php">Log In</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <!-- onsubmit means when the form is complete we send the data to validateForm method-->
            <form id="signupForm" action="#" onsubmit="return validateForm()">
                <h2>Ticket Tech Registration </h2>
                <!--link to login if you have an account-->
                <p>Already have an account? <a href="login.php">Log in here</a>.</p>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <span id="usernameError" class="error-message"></span>

                <!--E-mail-->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <span id="emailError" class="error-message"></span>
                <!--Password, 1st box-->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span id="passwordError" class="error-message"></span>
                <!--Password, 2nd box-->
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

            //Username Error Msg / checker
            if (username.trim() === "") {
                usernameError.innerHTML = "Username is required!";
                isValid = false;
            }
            //email Error Msg / checker
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailError.innerHTML = "Enter a valid email!";
                isValid = false;
            }
            //password Error Msg / checker
            if (password.length < 8) {
                passwordError.innerHTML = "Password Must be at least 8 characters long!";
                isValid = false;
            }
            //ensure password and confim are the same
            if (confirmPassword !== password) {
                confirmPasswordError.innerHTML = "passwords do not match!";
                isValid = false;
            }
            //user feedback for account validation
            if (isValid) {
                //alert("Account Successfuly Validated");
                saveUserInformation(username, email, password);

                // Print user data to console
                //**!!! get rid of before final submission or comment it out as this is bad for security !!!**
                console.log("User Data:", {
                    username,
                    email,
                    password
                });

                showSuccessMessage();
            }
            return isValid;
        }
        // Function to save user information to localStorage, this will change when we figure out db stuff as this only works client side not server side
        function saveUserInformation(username, email, password) {
            var userData = JSON.parse(localStorage.getItem("userData")) || [];

            userData.push({
                username: username,
                email: email,
                password: password
            });

            localStorage.setItem("userData", JSON.stringify(userData));
        }
        // Function to display success message and redirect to login page
        function showSuccessMessage() {
            var successMessage = document.getElementById("successMessage");
            successMessage.style.display = "block";
            setTimeout(function() {
                successMessage.style.display = "none";

                // Redirect to the login page
                console.log("Redirecting to login page");
                window.location.href = "login.php";
            }, 2000);
        }
    </script>
</body>

</html>