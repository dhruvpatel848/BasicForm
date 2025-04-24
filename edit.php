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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE no=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $fname = $row['fname'];
        $mname = $row['mname'];
        $lname = $row['lname'];
        $address = $row['address'];
        $email = $row['email'];
        $hobbies = $row['hobbies']; 
        $gender = $row['gender'];
        $currentImage = $row['image'];
        $status = $row['Status'];
    } else {
        die("User not found.");
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $hobbies = implode(',', $_POST['hobbies']);
    $gender = $_POST['gender'];
    $status = $_POST['status']; // Added status field

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photo = $_FILES['photo'];
        $photoName = $photo['name'];
        $photoTmpName = $photo['tmp_name'];
        $photoSize = $photo['size'];
        $photoError = $photo['error'];
        $photoType = $photo['type'];

        $photoExt = explode('.', $photoName);
        $photoActualExt = strtolower(end($photoExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($photoActualExt, $allowed)) {
            if ($photoError === 0) {
                if ($photoSize < 1000000) {
                    $photoNewName = "profile" . $id . "." . $photoActualExt;
                    $photoDestination = 'uploads/' . $photoNewName;
                    move_uploaded_file($photoTmpName, $photoDestination);
                } else {
                    die("Your file is too big!");
                }
            } else {
                die("There was an error uploading your file!");
            }
        } else {
            die("You cannot upload files of this type!");
        }
    } else {
        $photoDestination = $currentImage;
    }

    $sql = "UPDATE users SET fname=?, mname=?, lname=?, address=?, email=?, hobbies=?, gender=?, image=?, Status=? WHERE no=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $fname, $mname, $lname, $address, $email, $hobbies, $gender, $photoDestination, $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: home.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .edit-user-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .edit-user-container h2 {
            margin-bottom: 20px;
        }
        .edit-user-container .form-group {
            margin-bottom: 15px;
        }
        .edit-user-container .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="edit-user-container">
        <h2 class="text-center">Edit User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" class="form-control" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required>
            </div>
            <div class="form-group">
                <label for="mname">Middle Name:</label>
                <input type="text" class="form-control" name="mname" value="<?php echo htmlspecialchars($mname); ?>">
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" class="form-control" name="lname" value="<?php echo htmlspecialchars($lname); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="hobbies">Hobbies:</label><br>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="hobbies[]" value="Cricket" <?php if(strpos($hobbies, "Cricket") !== false) echo "checked"; ?>>
                    <label class="form-check-label" for="hobbies">Cricket</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="hobbies[]" value="Volleyball" <?php if(strpos($hobbies, "Volleyball") !== false) echo "checked"; ?>>
                    <label class="form-check-label" for="hobbies">Volleyball</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="hobbies[]" value="Football" <?php if(strpos($hobbies, "Football") !== false) echo "checked"; ?>>
                    <label class="form-check-label" for="hobbies">Football</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="hobbies[]" value="Gaming" <?php if(strpos($hobbies, "Gaming") !== false) echo "checked"; ?>>
                    <label class="form-check-label" for="hobbies">Gaming</label>
                </div>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="gender">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="gender">Female</label>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Status:</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="status" value="1" <?php echo ($status == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">Active</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="status" value="0" <?php echo ($status == 0) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">Inactive</label>
                </div>
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <input type="file" class="form-control-file" name="photo">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
