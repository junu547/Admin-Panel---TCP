<?php
require('fpdf/fpdf.php');
include('db_connection.php');

// Fetch student details by admission number
if (isset($_POST['admission_number'])) {
    $admission_number = $_POST['admission_number'];
    // Updated SQL query to join with departments table
    $student_query = "
        SELECT s.*, d.name AS department_name
        FROM students s
        JOIN departments d ON s.department_id = d.id
        WHERE s.admissionNumber='$admission_number'
    ";
    $student_result = $conn->query($student_query);

    if ($student_result->num_rows > 0) {
        $student = $student_result->fetch_assoc();
    } else {
        die('Student not found.');
    }
} else {
    die('No admission number provided.');
}

// Function to format JSON data
function formatJson($json) {
    if (empty($json)) {
        return 'N/A';
    }
    $data = json_decode($json, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
        $formatted = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $formatted .= ucfirst($key) . ': ' . implode(', ', $value) . "\n";
            } else {
                $formatted .= ucfirst($key) . ': ' . $value . "\n";
            }
        }
        return $formatted;
    }
    return $json;
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add College Logo and Details
$pdf->Image('images/amallogo.jpeg', 10, 10, 30); // Logo
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Amal College of Advanced Studies, Nilambur', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Phone: +91 123 456 7890', 0, 1, 'R');
$pdf->Cell(0, 10, 'Email: info@amalcollege.edu', 0, 1, 'R');
$pdf->Cell(0, 10, 'Address: Nilambur, Kerala', 0, 1, 'R');
$pdf->Ln(20);

// Add Student Details Heading
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Student Details', 0, 1, 'C');
$pdf->Ln(10);

// Add Student Details
$pdf->SetFont('Arial', 'B', 12);
$fields = [
    
    'Name' => $student['name'],
    'Department' => $student['department_name'], // Display department name
    'Programme' => $student['programme'],
    'Date of Birth' => $student['dob'],
    'Admission Number' => $student['admissionNumber'],
    'Category' => $student['category'],
    'Category of Admission' => $student['categoryOfAdmission'],
    'Address' => $student['address'],
    'Phone Number' => $student['phoneNumber'],
    'Email' => $student['email'],
    'Level' => formatJson($student['level']),
    'Ambition' => formatJson($student['ambition']),
    'Strength' => formatJson($student['strength']),
    'Parent Details' => formatJson($student['parentDetails']),
    'Hostel/Day Scholar' => formatJson($student['hostel']),
    'Academic Performance' => formatJson($student['academicPerformance']),
    'Extracurricular Activities' => formatJson($student['extracurricular']),
    'Entrepreneurship/Innovation' => formatJson($student['entrepreneurship']),
    'Attendance' => formatJson($student['attendance']),
    'University Exams (SGPA)' => formatJson($student['universityExams']),
    'Classroom Performance' => formatJson($student['classroomPerformance']),
    'Appreciation/Recognition' => formatJson($student['appreciationRecognition']),
    'Potential Identified' => formatJson($student['potentialIdentified']),
    'Club/Forum' => formatJson($student['clubForum']),
    'Part-Time Job' => formatJson($student['parttimeJob']),
    'Tutor Suggestions' => formatJson($student['tutorSuggestions']),
    'Strengthened Areas' => formatJson($student['strengthenedAreas']),
];

foreach ($fields as $label => $value) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $label . ': ' . $value, 0, 1);
}

// Footer
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Address: Nilambur, Kerala', 0, 1, 'C');
$pdf->Cell(0, 10, 'Email: info@amalcollege.edu', 0, 1, 'C');
$pdf->Cell(0, 10, 'Phone: +91 123 456 7890', 0, 1, 'C');

// Output PDF
$pdf->Output('I', 'Student_' . $admission_number . '.pdf');
?>
