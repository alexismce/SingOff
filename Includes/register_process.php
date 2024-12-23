<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    try {
        include 'db_connect.php'; // Include your database connection

        // Debugging outputs before checking existing username
        // echo "Connected to database: " . $dbname . "<br>";
        // echo "Form Username: " . htmlspecialchars($username) . "<br>";
        // echo "Form Password: " . htmlspecialchars($password) . "<br>";
        // echo "Form Email: " . htmlspecialchars($email) . "<br>";

        // Check if the username already exists
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Show debugging output for existing usernames
        if ($existingUser) {
            echo "Error: Username already exists.";
            exit();
        }

        // Debugging outputs for before and after insertion
        echo "Inserting new user..." . "<br>";
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $password, $email])) {
        // Debugging outputs for before and after insertion
        // echo "Inserting new user..." . "<br>";
            // header("Location: ../thank_you.php");
        } else {
            echo "Error: Could not execute statement.";
        }
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
