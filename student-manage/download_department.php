<?php
require('fpdf/fpdf.php');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch department id from form
$department_id = $_POST['department_id'];

// Updated SQL query to join with departments table
$sql = "
    SELECT s.admissionNumber, s.name, s.programme, d.name AS department_name, s.email
    FROM students s
    JOIN departments d ON s.department_id = d.id
    WHERE s.department_id = '$department_id'
";
$result = $conn->query($sql);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(10, 20, 10);

// Logo and College Name
$pdf->Image('images/amallogo.jpeg', 10, 10, 30);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Amal College of Advanced Studies, Nilambur', 0, 1, 'R');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Phone: +91 123 456 7890 | Email: info@amalcollege.edu', 0, 1, 'R');
$pdf->Ln(10);

// Department Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Department Wise Student Data', 0, 1);
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'Admission Number', 1);
$pdf->Cell(50, 10, 'Name', 1);
$pdf->Cell(30, 10, 'Programme', 1);
$pdf->Cell(50, 10, 'Department', 1);
$pdf->Cell(40, 10, 'Email', 1);
$pdf->Ln();

// Table Rows
$pdf->SetFont('Arial', '', 12);
while ($student = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $student['admissionNumber'], 1);
    $pdf->Cell(50, 10, $student['name'], 1);
    $pdf->Cell(30, 10, $student['programme'], 1);
    $pdf->Cell(50, 10, $student['department_name'], 1); // Display department name
    $pdf->Cell(40, 10, $student['email'], 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output();
?>
