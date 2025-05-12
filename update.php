<?php
// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

// Fetch current user data
$sql = "SELECT * FROM users WHERE id = $id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission for update
if (isset($_POST['update_user'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $sex = $_POST['sex'];

    $sql = "UPDATE users SET 
                first_name = '$first_name',
                last_name = '$last_name',
                email = '$email',
                birthdate = '$birthdate',
                sex = '$sex'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Redirect to main page after update
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef1f4;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color:rgb(14, 30, 252);
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .back {
            text-align: center;
            margin-top: 20px;
        }
        .back a {
            text-decoration: none;
            color: #007bff;
        }
        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Update User</h2>
    <form method="POST">
        <input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>
        <input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>
        <input type="email" name="email" value="<?= $user['email'] ?>" required>
        <input type="date" name="birthdate" value="<?= $user['birthdate'] ?>" required>
        <select name="sex" required>
            <option value="">Select Sex</option>
            <option value="Male" <?= $user['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $user['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
        <input type="submit" name="update_user" value="Update">
    </form>
    <div class="back">
        <a href="index.php">← Back to User List</a>
    </div>
</div>
</body>
</html>
