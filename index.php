<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form.registration, form.action-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        input, select {
            padding: 10px;
            width: calc(50% - 10px);
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"], .action-buttons button {
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-update {
            background-color: #28a745;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-update:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>User Registration</h2>
    <form method="POST" class="registration">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="date" name="birthdate" required>
        <select name="sex" required>
            <option value="">Select Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <input type="submit" name="submit" value="Register">
    </form>

    <h2>Registered Users</h2>

    <form method="POST" class="action-form">
        <table>
            <tr>
                <th>Select</th>
                <th>ID</th><th>Name</th><th>Email</th><th>Birthdate</th><th>Sex</th>
            </tr>
            <?php
            // DB connection
            $conn = new mysqli("localhost", "root", "", "user_registration");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle new registration
            if (isset($_POST['submit'])) {
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $birthdate = $_POST['birthdate'];
                $sex = $_POST['sex'];

                $sql = "INSERT INTO users (first_name, last_name, email, password, birthdate, sex) 
                        VALUES ('$first_name', '$last_name', '$email', '$password', '$birthdate', '$sex')";
                echo $conn->query($sql)
                    ? "<p style='color: green;'>User registered successfully.</p>"
                    : "<p style='color: red;'>Error: " . $conn->error . "</p>";
            }

            // Handle delete
            if (isset($_POST['delete']) && isset($_POST['user_id'])) {
                $id = $_POST['user_id'];
                $conn->query("DELETE FROM users WHERE id = $id");
            }

            // Handle update redirect
            if (isset($_POST['update']) && isset($_POST['user_id'])) {
                $id = $_POST['user_id'];
                header("Location: update.php?id=$id");
                exit();
            }

            // Fetch and display users
            $result = $conn->query("SELECT * FROM users");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td><input type='radio' name='user_id' value='{$row['id']}' required></td>
                            <td>{$row['id']}</td>
                            <td>{$row['first_name']} {$row['last_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['birthdate']}</td>
                            <td>{$row['sex']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }

            $conn->close();
            ?>
        </table>

        <div class="action-buttons">
            <button type="submit" name="update" class="btn-update">Update</button>
            <button type="submit" name="delete" class="btn-delete">Delete</button>
        </div>
    </form>
</div>

</body>
</html>
