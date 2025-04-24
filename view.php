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

    $sql = "SELECT users.no, users.fname, users.lname, users.mname, users.gender, users.hobbies, users.image, users.address, users.email, role.role 
            FROM users 
            JOIN role ON users.role_id = role.id
            WHERE users.no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    die("No user ID specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
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
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table img {
            max-width: 100px;
            height: auto;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>User Information</h2>
            </div>
            <div class="card-body">
                <?php if ($user): ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td><?php echo htmlspecialchars($user['no']); ?></td>
                        </tr>
                        <tr>
                            <th>First Name</th>
                            <td><?php echo htmlspecialchars($user['fname']); ?></td>
                        </tr>
                        <tr>
                            <th>Middle Name</th>
                            <td><?php echo htmlspecialchars($user['mname']); ?></td>
                        </tr>
                        <tr>
                            <th>Last Name</th>
                            <td><?php echo htmlspecialchars($user['lname']); ?></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td><?php echo htmlspecialchars($user['gender']); ?></td>
                        </tr>
                        <tr>
                            <th>Hobbies</th>
                            <td><?php echo htmlspecialchars($user['hobbies']); ?></td>
                        </tr>
                        <tr>
                            <th>Image</th>
                            <td><img src="<?php echo htmlspecialchars($user['image']); ?>" alt="User Image"></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo htmlspecialchars($user['address']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <p class="text-danger">User not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
