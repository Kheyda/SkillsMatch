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