<?php
// Database connection
$servername = "localhost";  // Change if necessary
$username = "root";  // Change if necessary
$password = "";  // Change if necessary
$dbname = "skills_match";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $name = $_POST['applicantName'];
  $surname = $_POST['applicantSurname'];
  $email = $_POST['applicantEmail'];
  $phone = $_POST['applicantPhone'];
  $dob = $_POST['applicantDOB'];
  $gender = $_POST['applicantGender'];

  // Handle CV upload
  $cv = $_FILES['applicantCV'];
  $cvName = $cv['name'];
  $cvTmpName = $cv['tmp_name'];
  $cvSize = $cv['size'];
  $cvError = $cv['error'];
  $cvType = $cv['type'];

  // Ensure the file is a PDF, DOC, or DOCX
  $cvExt = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));
  $allowed = array('pdf', 'doc', 'docx');

  if (in_array($cvExt, $allowed)) {
    // Check for errors
    if ($cvError === 0) {
      // Generate a unique name for the uploaded file
      $cvNewName = uniqid('', true) . "." . $cvExt;
      $cvDestination = 'uploads/' . $cvNewName;

      // Move the uploaded file to the destination folder
      if (move_uploaded_file($cvTmpName, $cvDestination)) {
        // Insert data into the database
        $sql = "INSERT INTO application (name, surname, email, phone, dob, gender, cv_path)
                        VALUES ('$name', '$surname', '$email', '$phone', '$dob', '$gender', '$cvDestination')";

        if ($conn->query($sql) === TRUE) {
          echo "Application submitted successfully!";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      } else {
        echo "There was an error uploading your CV.";
      }
    } else {
      echo "Error with the file upload.";
    }
  } else {
    echo "Invalid file type. Only PDF, DOC, and DOCX are allowed.";
  }
}

$conn->close();
