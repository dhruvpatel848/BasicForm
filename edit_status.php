<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "basic_form";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $new_status = ($user['Status'] == 0) ? 1 : 0;
        $sql = "UPDATE users SET status = ? WHERE no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_status, $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: home.php");
        exit();
    } else {
        echo "User not found.";
    }
}
?>
