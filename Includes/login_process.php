<?php
session_start();

// Error reporting (keep this for development, remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load environment variables
require_once '../vendor/autoload.php';
use Dotenv\Dotenv; // Ensure namespace is imported correctly

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Fetch environment variables
$servername = getenv('DB_SERVERNAME');
$dbusername = getenv('DB_USERNAME');
$dbpassword = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to SQL Server: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $row['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            // Redirect to the index page
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
}
?>