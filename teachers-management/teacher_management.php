<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getTeachers($conn, $dept) {
    $dept = $conn->real_escape_string($dept);
    $sql = $dept ? "SELECT * FROM sign_up_tb WHERE dept='$dept'" : "SELECT * FROM sign_up_tb";
    return $conn->query($sql);
}

function getDepartments($conn) {
    $sql = "SELECT * FROM departments";
    return $conn->query($sql);
}

function deleteTeacher($conn, $id) {
    $id = $conn->real_escape_string($id);
    $sql = "DELETE FROM sign_up_tb WHERE id='$id'";
    return $conn->query($sql);
}

function updateDepartment($conn, $id, $dept) {
    $id = $conn->real_escape_string($id);
    $dept = $conn->real_escape_string($dept);
    $sql = "UPDATE sign_up_tb SET dept='$dept' WHERE id='$id'";
    return $conn->query($sql);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        if (isset($_POST['id'])) {
            deleteTeacher($conn, $_POST['id']);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_department') {
        if (isset($_POST['id']) && isset($_POST['new_dept'])) {
            updateDepartment($conn, $_POST['id'], $_POST['new_dept']);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Teacher Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        .filter-container {
            margin-bottom: 20px;
        }
        .filter-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .teacher-table th, .teacher-table td {
            text-align: center;
        }
        .btn-danger, .btn-warning {
            cursor: pointer;
        }
        .alert-box, .update-department-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 1000;
        }
        .alert-box h4, .update-department-box h4 {
            margin-top: 0;
        }
        .alert-box button, .update-department-box button {
            margin-right: 10px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
    <script>
        function confirmDelete(id) {
            document.getElementById('alert-box').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('teacher-id').value = id;
        }

        function openUpdateDepartment(id, currentDept) {
            document.getElementById('update-department-box').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('update-teacher-id').value = id;
            document.getElementById('current-dept').value = currentDept;
        }

        function closeAlert() {
            document.getElementById('alert-box').style.display = 'none';
            document.getElementById('update-department-box').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('confirm-btn').addEventListener('click', function() {
                document.getElementById('delete-form').submit();
            });

            document.getElementById('cancel-btn').addEventListener('click', closeAlert);
            
            document.getElementById('update-department-btn').addEventListener('click', function() {
                document.getElementById('update-department-form').submit();
            });

            document.getElementById('update-cancel-btn').addEventListener('click', closeAlert);
        });
    </script>
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

    <div class="container">
        <h2>Teacher Management</h2>

        <!-- Filter Options -->
        <div class="filter-container">
            <form method="GET">
                <label for="dept">Filter by Department:</label>
                <select name="dept" id="dept" class="filter-select">
                    <option value="">All Departments</option>
                    <?php
                    $departments = getDepartments($conn);
                    while ($dept = $departments->fetch_assoc()) {
                        echo "<option value='{$dept['name']}' " . (isset($_GET['dept']) && $_GET['dept'] == $dept['name'] ? 'selected' : '') . ">{$dept['name']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary mt-2">Filter</button>
            </form>
        </div>

        <!-- Teachers Table -->
        <h2>Teachers</h2>
        <?php
        $dept = $_GET['dept'] ?? '';
        $teachers = getTeachers($conn, $dept);
        if ($teachers->num_rows > 0) {
            echo "<table class='table table-striped teacher-table'>";
            echo "<thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Address</th>
                        <th>Mobile No</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Qualification</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";
            while ($row = $teachers->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['dept']}</td>";
                echo "<td>{$row['address']}</td>";
                echo "<td>{$row['mobile_no']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['designation']}</td>";
                echo "<td>{$row['qualification']}</td>";
                echo "<td>{$row['date']}</td>";
                echo "<td>
                        <button class='btn btn-danger btn-sm' onclick='confirmDelete({$row['id']})'>Delete</button>
                        <button class='btn btn-info btn-sm' onclick='openUpdateDepartment({$row['id']}, \"{$row['dept']}\")'>Change Department</button>
                    </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Sorry, No teachers available.</p>";
        }
        ?>
    </div>

    <!-- Delete Confirmation Alert Box -->
    <div id="overlay" class="overlay"></div>
    <div id="alert-box" class="alert-box">
        <h4>Are you sure you want to delete this teacher?</h4>
        <form id="delete-form" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="teacher-id">
            <button type="button" id="confirm-btn" class="btn btn-danger">Delete</button>
            <button type="button" id="cancel-btn" class="btn btn-secondary">Cancel</button>
        </form>
    </div>

    <!-- Change Department Alert Box -->
    <div id="update-department-box" class="update-department-box">
        <h4>Change Department</h4>
        <form id="update-department-form" method="POST">
            <input type="hidden" name="action" value="update_department">
            <input type="hidden" name="id" id="update-teacher-id">
            <label for="new_dept">New Department:</label>
            <select name="new_dept" id="new_dept" class="filter-select">
                <?php
                $departments = getDepartments($conn);
                while ($dept = $departments->fetch_assoc()) {
                    echo "<option value='{$dept['name']}'>{$dept['name']}</option>";
                }
                ?>
            </select>
            <button type="button" id="update-department-btn" class="btn btn-success mt-2">Update</button>
            <button type="button" id="update-cancel-btn" class="btn btn-secondary mt-2">Cancel</button>
        </form>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Teachers Companion. All rights reserved.
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
