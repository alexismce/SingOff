<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php"); // Redirect to the main page (index.php)
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css?v=1.0"> <!-- Cache busting -->
  <link rel="stylesheet" href="css/style.css?v=1.0"> <!-- Cache busting, adjusted path -->
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <form method="post" action="includes/login_process.php"> <!-- Corrected action path -->
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required autocomplete="username">
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
<script>
  window.addEventListener('beforeunload', function(event) {
    // Display a warning message to the user
    event.preventDefault(); // For modern browsers
    event.returnValue = ''; // For older browsers
  });
</script>

</body>
</html>
