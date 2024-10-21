<?php
session_start(); // Start a session to store user information

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'skills_match');

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare the SQL statement
  $sql = "SELECT password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param('s', $username);
  $stmt->execute();

  // Store the result to check if the username exists
  $stmt->store_result();

  // Check if the username exists
  if ($stmt->num_rows > 0) {
    // Bind the result to a variable
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Password is correct, set session variables and redirect
      $_SESSION['username'] = $username; // Store username in session
      header("Location: index.php"); // Redirect to home page
      exit();
    } else {
      // Invalid password
      echo "<script>alert('Invalid username or password. Please try again.');</script>";
    }
  } else {
    // Username does not exist
    echo "<script>alert('Invalid username or password. Please try again.');</script>";
  }

  // Close the statement
  $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SkillsMatch</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f8f9fa;
    }

    .form-container {
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>

  <!-- Login Form -->
  <div class="form-container">
    <h2>Login</h2>
    <form id="loginForm" action="login.php" method="POST">
      <div class="form-group mb-3">
        <label for="loginUsername">Username:</label>
        <input type="text" class="form-control" id="loginUsername" name="username" placeholder="Enter your username" required>
      </div>
      <div class="form-group mb-3">
        <label for="loginPassword">Password:</label>
        <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="mt-3 text-center">Not registered yet? <a href="register.html">Sign Up</a></p>
    <p class="mt-3 text-center">Forgot Password? <a href="forgotpassword.html">Click Here</a></p>
  </div>

</body>

</html>