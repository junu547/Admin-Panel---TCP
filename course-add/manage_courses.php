<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses
$courses = $conn->query("SELECT * FROM departments")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add_course') {
        $course_type = $_POST['course_type'];
        $name = $_POST['name'];

        $sql = "INSERT INTO departments (course_type, name) VALUES ('$course_type', '$name')";
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_courses.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif ($_POST['action'] === 'delete_course') {
        $id = $_POST['id'];

        $sql = "DELETE FROM departments WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_courses.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif ($_POST['action'] === 'edit_course') {
        $id = $_POST['id'];
        $course_type = $_POST['course_type'];
        $name = $_POST['name'];

        $sql = "UPDATE departments SET course_type='$course_type', name='$name' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_courses.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Teachers Companion</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .header, .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            /* padding: 10px 0; */
        }

        .navbar {
            background-color: #343a40;
        }

        .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            background-color: #495057;
            color: white !important;
        }

        .container {
            flex: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            overflow: hidden;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        table th {
            background-color: #495057;
            color: white;
        }

        table td {
            background-color: #f8f9fa;
        }

        table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background-color: #343a40;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #495057;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .footer {
            padding: 10px 0;
            position: relative;
            width: 100%;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            table, label, input, button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- <header class="header">
        <h1>Teachers Companion</h1>
    </header> -->
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
        <h2>Manage Courses</h2>
        <h3>Current Courses</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Type</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= htmlspecialchars($course['id']) ?></td>
                        <td><?= htmlspecialchars($course['course_type']) ?></td>
                        <td><?= htmlspecialchars($course['name']) ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-primary" onclick="editCourse(<?= htmlspecialchars($course['id']) ?>, '<?= htmlspecialchars($course['course_type']) ?>', '<?= htmlspecialchars($course['name']) ?>')">Edit</button>
                                <button class="btn btn-danger" onclick="confirmDelete(<?= htmlspecialchars($course['id']) ?>)">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add New Course</h3>
        <form method="POST" action="manage_courses.php">
            <input type="hidden" name="action" value="add_course">
            <div class="form-group">
                <label for="course_type">Course Type:</label>
                <select id="course_type" name="course_type" required>
                    <option value="Aided">Aided</option>
                    <option value="BVoc">BVoc</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Course Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <button type="submit">Add Course</button>
        </form>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="manage_courses.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_course">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_course_type">Course Type:</label>
                            <select id="edit_course_type" name="course_type" required>
                                <option value="Aided">Aided</option>
                                <option value="BVoc">BVoc</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_name">Course Name:</label>
                            <input type="text" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="manage_courses.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this course?
                        <input type="hidden" name="action" value="delete_course">
                        <input type="hidden" id="delete_id" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        &copy; <?php echo date("Y"); ?> Teachers Companion. All rights reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function editCourse(id, courseType, name) {
            $('#edit_id').val(id);
            $('#edit_course_type').val(courseType);
            $('#edit_name').val(name);
            $('#editModal').modal('show');
        }

        function confirmDelete(id) {
            $('#delete_id').val(id);
            $('#deleteModal').modal('show');
        }
    </script>
</body>

</html>
