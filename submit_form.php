<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siwes_submissions";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data
$matric_no = $_POST['matric'];

// Check if matric number already exists
$check_query = $conn->prepare("SELECT * FROM student_submissions WHERE matric_number = ?");
$check_query->bind_param("s", $matric_no);
$check_query->execute();
$check_query->store_result();

if ($check_query->num_rows > 0) {
    // Matric number already exists, show alert and stop submission
    echo '<script>
        alert("Error: This matriculation number has already been submitted.");
        window.location.href = "index.html"; // Redirect back to the form page
    </script>';
} else {
    // Proceed with insertion
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $student_email = $_POST['student_email'];
    $faculty = $_POST['faculty'];
    $department = $_POST['department'];
    $assumption_date = $_POST['assumption_date'];
    $it_state = $_POST['it_state'];
    $establishment = $_POST['establishment'];
    $establishment_phone = $_POST['establishment_phone'];
    $establishment_email = $_POST['establishment_email'];
    $supervisor_name = $_POST['supervisor_name'];
    $supervisor_phone = $_POST['supervisor_phone'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO student_submissions (name, matric_number, phone, email, faculty, department, assumption_date, it_state, establishment, establishment_phone, establishment_email, supervisor_name, supervisor_phone, submission_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("sssssssssssss", $name, $matric_no, $phone, $student_email, $faculty, $department, $assumption_date, $it_state, $establishment, $establishment_phone, $establishment_email, $supervisor_name, $supervisor_phone);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the form page with success message
        echo '<script>
            alert("New record created successfully.");
            window.location.href = "index.html"; // Replace with the path to your form page
        </script>';
    } else {
        // Show error message and redirect back
        echo '<script>
            alert("Error: ' . $stmt->error . '");
            window.location.href = "index.html"; // Replace with the path to your form page
        </script>';
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
