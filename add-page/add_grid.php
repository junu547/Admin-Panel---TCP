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

$teachers = $conn->query("SELECT * FROM sign_up_tb")->fetch_all(MYSQLI_ASSOC);
$grids = $conn->query("SELECT * FROM grids")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add_grid') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $url = $_POST['url'];
        $teacher_id = $_POST['teacher_id'];

        $sql = "INSERT INTO grids (title, description, url, teacher_id) VALUES ('$title', '$description', '$url', $teacher_id)";
        $conn->query($sql);
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($_POST['action'] === 'delete_grid') {
        $grid_id = $_POST['grid_id'];

        $sql = "DELETE FROM grids WHERE id=$grid_id";
        $conn->query($sql);
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($_POST['action'] === 'edit_grid') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $url = $_POST['url'];
        $teacher_id = $_POST['teacher_id'];

        $sql = "UPDATE grids SET title='$title', description='$description', url='$url', teacher_id='$teacher_id' WHERE id=$id";
        $conn->query($sql);
        header("Location: admin_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Teachers Companion</title>
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
            padding: 10px 0;
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

        input[type="text"], textarea, select {
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

        .actions form {
            display: inline-block;
        }

        .footer {
            padding: 10px 0;
            position: relative;
            width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            table, label, input, textarea, select, button {
                font-size: 14px;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- <header class="header">
      >
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
    <div class="container fade-in">
        <h2>Page Assign</h2>
        <h3>Teachers</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= htmlspecialchars($teacher['id']) ?></td>
                        <td><?= htmlspecialchars($teacher['name']) ?></td>
                        <td><?= htmlspecialchars($teacher['dept']) ?></td>
                        <td>
                            <!-- You can add more actions here if needed -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add Grid</h3>
        <form method="POST" action="add_grid.php">
            <input type="hidden" name="action" value="add_grid">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="url">URL:</label>
                <input type="text" id="url" name="url" required>
            </div>
            <div class="form-group">
                <label for="teacher_id">Assign to Teacher:</label>
                <select id="teacher_id" name="teacher_id" required>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= htmlspecialchars($teacher['id']) ?>"><?= htmlspecialchars($teacher['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Add Grid</button>
        </form>

        <h3>Existing Grids</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grids as $grid): ?>
                    <tr>
                        <td><?= htmlspecialchars($grid['id']) ?></td>
                        <td><?= htmlspecialchars($grid['title']) ?></td>
                        <td><?= htmlspecialchars($grid['description']) ?></td>
                        <td><a href="<?= htmlspecialchars($grid['url']) ?>" target="_blank"><?= htmlspecialchars($grid['url']) ?></a></td>
                        <td><?= htmlspecialchars($grid['teacher_id']) ?></td>
                        <td class="actions">
                            <button type="button" class="btn btn-primary" onclick="editGrid(<?= $grid['id'] ?>, '<?= htmlspecialchars($grid['title']) ?>', '<?= htmlspecialchars($grid['description']) ?>', '<?= htmlspecialchars($grid['url']) ?>', <?= $grid['teacher_id'] ?>)">Edit</button>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $grid['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="add_grid.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Grid</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_grid">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_title">Title:</label>
                            <input type="text" id="edit_title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Description:</label>
                            <textarea id="edit_description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_url">URL:</label>
                            <input type="text" id="edit_url" name="url" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_teacher_id">Assign to Teacher:</label>
                            <select id="edit_teacher_id" name="teacher_id" required>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= htmlspecialchars($teacher['id']) ?>"><?= htmlspecialchars($teacher['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="admin_dashboard.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="delete_grid">
                        <input type="hidden" id="delete_id" name="grid_id">
                        <p>Are you sure you want to delete this grid?</p>
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
        function editGrid(id, title, description, url, teacherId) {
            $('#edit_id').val(id);
            $('#edit_title').val(title);
            $('#edit_description').val(description);
            $('#edit_url').val(url);
            $('#edit_teacher_id').val(teacherId);
            $('#editModal').modal('show');
        }

        function confirmDelete(id) {
            $('#delete_id').val(id);
            $('#deleteModal').modal('show');
        }
    </script>
</body>

</html>
