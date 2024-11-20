<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Find A Job - Skills Match</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Job match platform" name="description">
    <meta content="job search, skills match, employment" name="keywords">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <a href="index.html" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                <h1 class="m-0 text-secondary">Skills-Match Website</h1>
            </a>
        </nav>
        <!-- Navbar End -->

    </div>

    <?php
    // Database connection details for XAMPP with default phpMyAdmin settings
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "skills_match";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Collect form data
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $dob = $_POST['date_of_birth'];
        $gender = $_POST['gender'];
        $experience = $_POST['years_of_experience'];
        $notice_period = $_POST['notice_period'];

        // Insert query
        $sql = "INSERT INTO application (name, surname, email, phone, date_of_birth, gender, years_of_experience, notice_period)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

        if ($conn->query($sql) === TRUE) {
            echo "<div class='container'><div class='alert alert-success'>Application submitted successfully!</div></div>";
        } else {
            echo "<div class='container'><div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div></div>";
        }

        // Close the connection
        $conn->close();
    }
    ?>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
