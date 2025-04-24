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
    if (isset($_POST['delete-selected-button']) && isset($_POST['selected'])) {
        $selected = $_POST['selected'];
        if (!empty($selected)) {
            foreach ($selected as $id) {
                $sql = "DELETE FROM users WHERE no = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }
            header("Location: home.php");
            exit();
        } else {
            echo "Please select at least one item to delete.";
        }
    } elseif (isset($_POST['delete-all-button'])) {
        $sql = "DELETE FROM users";
        if ($conn->query($sql) === TRUE) {
            header("Location: home.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .container {
            margin-top: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .action-buttons button {
            margin-right: 5px;
        }
        .custom-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-custom {
            margin-right: 5px;
        }
        .action-buttons .btn {
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">User List</h2>
        <form method="post" class="mb-3">
            <button type="submit" name="delete-all-button" class="btn btn-danger btn-custom">Delete All</button>
            <button type="submit" name="delete-selected-button" class="btn btn-warning btn-custom">Delete Selected</button>
        </form>
        <table class="table table-bordered custom-table">
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
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selected[]' value='" . htmlspecialchars($row['no']) . "'></td>";
                        echo "<td>" . htmlspecialchars($row['no']) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='User Image'></td>";
                        echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['mname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['lname']) . "</td>";
                        echo "<td>" . ($row['Status'] == 1 ? "Active" : "Inactive") . "</td>";
                        echo "<td class='action-buttons'>";
                        echo "<a href='view.php?id=" . htmlspecialchars($row['no']) . "' class='btn btn-info btn-sm'>View</a>";
                        echo "<button type='button' class='btn btn-primary btn-sm' onclick='editUser(" . htmlspecialchars($row['no']) . ")'>Edit</button>";
                        echo "<button type='button' class='btn btn-secondary btn-sm' onclick='editStatus(" . htmlspecialchars($row['no']) . ")'>Edit Status</button>";
                        echo "<button type='button' class='btn btn-danger btn-sm' onclick='deleteUser(" . htmlspecialchars($row['no']) . ")'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-success mt-3">Insert</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editUser(id) {
            window.location.href = 'edit.php?id=' + id;
        }
        function deleteUser(id) {
            window.location.href = 'delete.php?id=' + id;
        }
        function editStatus(id) {
            window.location.href = 'edit_status.php?id=' + id; 
        }
    </script>
</body>
</html>
