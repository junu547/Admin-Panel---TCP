<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['admissionNumber'])) {
    $admissionNumber = $_GET['admissionNumber'];
    $sql = "SELECT * FROM students WHERE admissionNumber='$admissionNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo "<h2>Student Details</h2>";
        echo "<p><strong>Admission Number:</strong> " . $student['admissionNumber'] . "</p>";
        echo "<p><strong>Name:</strong> " . $student['name'] . "</p>";
        echo "<p><strong>Programme:</strong> " . $student['programme'] . "</p>";
        echo "<p><strong>Department:</strong> " . $student['department_id'] . "</p>";
        echo "<p><strong>Category:</strong> " . $student['category'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $student['dob'] . "</p>";
        echo "<p><strong>Address:</strong> " . $student['address'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $student['phoneNumber'] . "</p>";
        echo "<p><strong>Email:</strong> " . $student['email'] . "</p>";
        // Add more fields as necessary
    } else {
        echo "No student found.";
    }
}

$conn->close();
?>
