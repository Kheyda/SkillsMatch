<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - SkillsMatch</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <div class="form-container">
    <h2>Register</h2>
    <form id="registrationForm" action="register.php" method="POST">
      <div class="form-group mb-3">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>
      <div class="form-group mb-3">
        <label for="surname">Surname:</label>
        <input type="text" class="form-control" id="surname" name="surname" required>
      </div>
      <div class="form-group mb-3">
        <label for="phone">Phone:</label>
        <input type="text" class="form-control" id="phone" name="phone" required>
      </div>
      <div class="form-group mb-3">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group mb-3">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group mb-3">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="form-group mb-3">
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
  </div>

  <?php
  // Connect to the database
  $conn = new mysqli('localhost', 'root', '', 'skills_match');

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
      echo "<p style='color: red;'>Passwords do not match.</p>";
    } else {
      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Prepare the SQL statement
      $sql = "INSERT INTO users (name, surname, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);

      // Bind parameters
      $stmt->bind_param('ssssss', $name, $surname, $phone, $email, $username, $hashedPassword);

      try {
        // Execute the statement
        $stmt->execute();
        echo "<p style='color: green;'>Registration successful. Redirecting to login page...</p>";
        header("refresh:2;url=login.php"); // Redirect after 2 seconds
      } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) { // Error code for duplicate entry
          echo "<p style='color: red;'>Username already exists. Please choose a different username.</p>";
        } else {
          echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
      }

      $stmt->close();
    }
  }

  $conn->close();
  ?>
</body>

</html>