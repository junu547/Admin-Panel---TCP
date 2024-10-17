<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name FROM departments";
$result = $conn->query($sql);

$departments = array();

while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

echo json_encode($departments);

$conn->close();
?>
