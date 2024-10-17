<?php
require('db_connection.php');

// Fetch departments for the department dropdown
$departments_query = "SELECT * FROM departments";
$departments_result = $conn->query($departments_query);

// Default students query
$students_query = "SELECT students.*, departments.name as department_name FROM students 
                   LEFT JOIN departments ON students.department_id = departments.id";
$students_result = $conn->query($students_query);

// Handle search by admission number
if (isset($_POST['search_admission_number'])) {
    $admission_number = $conn->real_escape_string($_POST['search_admission_number']);
    $students_query = "SELECT students.*, departments.name as department_name FROM students 
                       LEFT JOIN departments ON students.department_id = departments.id 
                       WHERE students.admissionNumber='$admission_number'";
    $students_result = $conn->query($students_query);
}

// Handle filter by department
if (isset($_POST['select_department']) && !empty($_POST['select_department'])) {
    $department_id = $conn->real_escape_string($_POST['select_department']);
    $students_query = "SELECT students.*, departments.name as department_name FROM students 
                       LEFT JOIN departments ON students.department_id = departments.id 
                       WHERE students.department_id='$department_id'";
    $students_result = $conn->query($students_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

header {
    background-color: #333;
    color: #fff;
    padding: 10px 0;
    text-align: center;
}

.container {
    width: 80%;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.top-buttons {
    margin-bottom: 20px;
}

form {
    margin-bottom: 20px;
}

select, input[type="text"], button {
    padding: 10px;
    margin-right: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
}

button {
    background-color: #333;
    color: #fff;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #555;
}
.nav-item a:hover {
            background-color: #ddd;
            color: black !important;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../admin_dashboard.php">Home</a>
                </li>
            
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <center><h2>Student Management</h2></center>
    <div class="container">
        <div class="top-buttons">
            <form action="download_department.php" method="post" style="display:inline;">
                <label for="department_id">Select Department:</label>
                <select name="department_id" id="department_id">
                    <option value="">Select Department</option>
                    <?php while ($row = $departments_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Download Department Data</button>
            </form>
            <form action="download_all.php" method="post" style="display:inline;">
                <button type="submit">Download All Data</button>
            </form>
        </div>

        <form action="student_management.php" method="post">
            <input type="text" name="search_admission_number" placeholder="Search by Admission Number" value="<?php echo isset($_POST['search_admission_number']) ? htmlspecialchars($_POST['search_admission_number']) : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <form action="student_management.php" method="post">
            <label for="select_department">Filter by Department:</label>
            <select name="select_department" id="select_department">
                <option value="">All Departments</option>
                <?php
                // Re-fetch departments to ensure the latest data
                $departments_result = $conn->query($departments_query);
                while ($row = $departments_result->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo (isset($_POST['select_department']) && $_POST['select_department'] == $row['id']) ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>
            <button type="submit">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Admission Number</th>
                    <th>Name</th>
                    <th>Programme</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($students_result->num_rows > 0) {
                    while ($student = $students_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $student['admissionNumber']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['programme']; ?></td>
                            <td><?php echo $student['department_name']; ?></td>
                            <td>
                                <form action="download_student.php" method="post" style="display:inline;">
                                    <input type="hidden" name="admission_number" value="<?php echo $student['admissionNumber']; ?>">
                                    <button type="submit">Download</button>
                                </form>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5">No students found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

<div class="footer">
        &copy; <?php echo date("Y"); ?> Teachers Companion. All rights reserved.
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</html>
<?php $conn->close(); ?>
