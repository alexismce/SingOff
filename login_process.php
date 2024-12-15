<?php
session_start();

// Error reporting (keep this for development, remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
require_once 'db_connect.php';

// Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

try {
    // Prepare and execute the SQL statement to fetch the user
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            
            // Redirect to the desired page
            header("Location: ../index.php");
            exit();
        } else {
            // Invalid password
            echo "Invalid username or password.";
        }
    } else {
        // Invalid username
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
} catch (mysqli_sql_exception $e) {
    die("Database error: " . $e->getMessage());
}
?>
