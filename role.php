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

$roles = [];
$role_query = "SELECT id, role FROM role";
$result = $conn->query($role_query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Role</title>
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
        .form-group label {
            font-weight: bold;
        }
        .form-check-label {
            margin-left: 0.3rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Edit Role</h2>
            </div>
            <div class="card-body">
                <form method="post" action="update_role.php">
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select a role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo $role['role']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="permissions">Permissions</label><br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="perm1" name="permissions[]" value="Insert">
                            <label class="form-check-label" for="perm1">Insert</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="perm2" name="permissions[]" value="Edit">
                            <label class="form-check-label" for="perm2">Edit</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="perm3" name="permissions[]" value="View">
                            <label class="form-check-label" for="perm3">View</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="perm4" name="permissions[]" value="Delete">
                            <label class="form-check-label" for="perm4">Delete</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
