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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role_id = $_POST['role'];

    $role_query = "SELECT role FROM role WHERE id=?";
    $stmt = $conn->prepare($role_query);
    $stmt->bind_param("i", $role_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $role = $row['role'];

    $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

    $permissionsArray = [];
    foreach ($permissions as $key => $permission) {
        $permissionsArray[] = $permission;
    }


    $permissionsJson = (json_encode($permissionsArray, JSON_FORCE_OBJECT));
   

    $sql = "UPDATE role SET permission=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $permissionsJson, $role_id);

    if ($stmt->execute()) {
        echo "Role permissions updated successfully for role: " . $role;
        header("Location: role.php");
    } else {
        echo "Error updating role permissions: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
