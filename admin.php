<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Your existing admin.php code starts here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siwes_submissions";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM student_submissions ORDER BY submission_date DESC");

// Handle download request
if (isset($_GET['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="siwes_submissions.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Name', 'Matric Number', 'Phone', 'Email', 'Faculty', 'Department', 'Assumption Date', 'IT State', 'Establishment', 'Establishment Phone', 'Establishment Email', 'Supervisor Name', 'Supervisor Phone', 'Submission Date'));
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIWES Submissions Admin</title>
</head>
<body>
    <h1>SIWES Submissions</h1>
    <a href="?download=1">Download CSV</a>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Matric Number</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Faculty</th>
            <th>Department</th>
            <th>Assumption Date</th>
            <th>IT State</th>
            <th>Establishment</th>
            <th>Establishment Phone</th>
            <th>Establishment Email</th>
            <th>Supervisor Name</th>
            <th>Supervisor Phone</th>
            <th>Submission Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['matric_number']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['faculty']); ?></td>
            <td><?php echo htmlspecialchars($row['department']); ?></td>
            <td><?php echo htmlspecialchars($row['assumption_date']); ?></td>
            <td><?php echo htmlspecialchars($row['it_state']); ?></td>
            <td><?php echo htmlspecialchars($row['establishment']); ?></td>
            <td><?php echo htmlspecialchars($row['establishment_phone']); ?></td>
            <td><?php echo htmlspecialchars($row['establishment_email']); ?></td>
            <td><?php echo htmlspecialchars($row['supervisor_name']); ?></td>
            <td><?php echo htmlspecialchars($row['supervisor_phone']); ?></td>
            <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<a href="logout.php">Logout</a>

</body>
</html>
