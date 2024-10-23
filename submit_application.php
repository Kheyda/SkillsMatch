<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Application - SkillsMatch</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <div class="form-container">
    <h2>Submit Application</h2>
    <form id="applicationForm" action="submit_application.php" method="POST" enctype="multipart/form-data">
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
        <label for="dob">Date of Birth:</label>
        <input type="date" class="form-control" id="dob" name="dob" required>
      </div>
      <div class="form-group mb-3">
        <label for="cv">Upload CV:</label>
        <input type="file" class="form-control" id="cv" name="cv" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Submit Application</button>
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
    $dob = $_POST['dob'];

    // Handle file upload (CV)
    $targetDir = "uploads/"; // Ensure this directory exists with proper permissions
    $cv = basename($_FILES["cv"]["name"]);
    $targetFilePath = $targetDir . $cv;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allow certain file formats
    $allowedFileTypes = array('pdf', 'doc', 'docx');
    if (in_array($fileType, $allowedFileTypes)) {
      // Upload file to server
      if (move_uploaded_file($_FILES["cv"]["tmp_name"], $targetFilePath)) {
        // Prepare the SQL statement
        $sql = "INSERT INTO application (name, surname, phone, email, date_of_birth, cv_path) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param('ssssss', $name, $surname, $phone, $email, $dob, $targetFilePath);

        try {
          // Execute the statement
          $stmt->execute();
          echo "<p style='color: green;'>Application submitted successfully.</p>";
        } catch (mysqli_sql_exception $e) {
          echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }

        $stmt->close();
      } else {
        echo "<p style='color: red;'>Sorry, there was an error uploading your file.</p>";
      }
    } else {
      echo "<p style='color: red;'>Only PDF, DOC, and DOCX files are allowed.</p>";
    }
  }

  $conn->close();
  ?>
</body>

</html>