<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Teachers Companion</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f0f0f0;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 15px;
            text-align: center;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }

        .card-body p {
            font-size: 18px;
            font-weight: 500;
            color: #333;
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

        .container {
            margin-top: 80px;
        }

        .card-deck {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
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
                    <a class="nav-link" href="admin_dashboard.php">Home</a>
                </li>
            
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card-deck">
            <div class="card">
                <a href="course-add\manage_courses.php" class="stretched-link">
                    <img src="icons/add-course.png" alt="Course Add" class="card-img-top mx-auto d-block">
                    <div class="card-body">
                        <p class="card-text">Course Add</p>
                    </div>
                </a>
            </div>

            <div class="card">
                <a href="add-page\add_grid.php" class="stretched-link">
                    <img src="icons/add_page.png" alt="Page Assign" class="card-img-top mx-auto d-block">
                    <div class="card-body">
                        <p class="card-text">Page Assign</p>
                    </div>
                </a>
            </div>

            <div class="card">
                <a href="student-manage\student_management.php" class="stretched-link">
                    <img src="icons/manage-students.png" alt="Manage Students" class="card-img-top mx-auto d-block">
                    <div class="card-body">
                        <p class="card-text">Manage Students</p>
                    </div>
                </a>
            </div>
            
            <div class="card">
                <a href="teachers-management\teacher_management.php" class="stretched-link">
                    <img src="icons/manage-teachers.png" alt="Manage Teachers" class="card-img-top mx-auto d-block">
                    <div class="card-body">
                        <p class="card-text">Manage Teachers</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Teachers Companion. All rights reserved.
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
