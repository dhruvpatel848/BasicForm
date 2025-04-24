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

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];

$sql = "SELECT users.*, role.role, role.permission FROM users 
        JOIN role ON users.role_id = role.id WHERE users.no=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $fname = $row['fname'];
    $role = $row['role'];
    $permissions = json_decode($row['permission'], true);
} else {
    die('User Not Found');
}

$sql_all = "SELECT * FROM users WHERE no != ?";
$stmt_all = $conn->prepare($sql_all);
$stmt_all->bind_param("i", $id);
$stmt_all->execute();
$result_all = $stmt_all->get_result();

$all_users = [];
while ($row_all = $result_all->fetch_assoc()) {
    $all_users[] = $row_all;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .table-img {
            width: 50px;
            height: auto;
        }
        .btn-custom {
            margin-right: 5px;
        }
        .logout-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="text-right">Logged in as: <?php echo htmlspecialchars($fname); ?></p>
        
        <h2>Users</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Select</th>
                        <th>No</th>
                        <th>Image</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($all_users) > 0): ?>
                        <?php foreach ($all_users as $user): ?>
                            <tr>
                                <td><input type="checkbox" name="selected[]" value="<?php echo htmlspecialchars($user['no']); ?>"></td>
                                <td><?php echo htmlspecialchars($user['no']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($user['image']); ?>" alt="User Image" class="table-img"></td>
                                <td><?php echo htmlspecialchars($user['fname']); ?></td>
                                <td><?php echo htmlspecialchars($user['mname']); ?></td>
                                <td><?php echo htmlspecialchars($user['lname']); ?></td>
                                <td><?php echo $user['Status'] == 1 ? "Active" : "Inactive"; ?></td>
                                <td>
                                    <?php if (in_array("View", $permissions)): ?>
                                        <a href="view.php?id=<?php echo htmlspecialchars($user['no']); ?>" class="btn btn-info btn-custom">View</a>
                                    <?php endif; ?>
                                    <?php if (in_array("Edit", $permissions)): ?>
                                        <button type="button" class="btn btn-warning btn-custom" onclick="editUser(<?php echo htmlspecialchars($user['no']); ?>)">Edit</button>
                                    <?php endif; ?>
                                    <?php if (in_array("Insert", $permissions)): ?>
                                        <button type="button" class="btn btn-success btn-custom" onclick="insert()">Insert</button>
                                    <?php endif; ?>
                                    <?php if (in_array("Delete", $permissions)): ?>
                                        <button type="button" class="btn btn-danger btn-custom" onclick="deleteUser(<?php echo htmlspecialchars($user['no']); ?>)">Delete</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No users found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <a href="logout.php" class="btn btn-secondary logout-button">Logout</a>
    </div>

    <script>
        function editUser(id) {
            window.location.href = 'edit.php?id=' + id;
        }
        
        function insert() {
            window.location.href = 'index.php';
        }
        
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
