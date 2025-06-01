<?php
session_start();
$conn = new mysqli("localhost", "root", "", "voting_system");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM admin WHERE username='$user'");

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();

        // Plain text password check (NOTE: For testing only)
        if ($pass === $row['password']) {
            $_SESSION['admin'] = $row['id'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "<div class='message error'>❌ Invalid password.</div>";
        }
    } else {
        $message = "<div class='message error'>❌ Invalid username.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login | Online Voting System</title>
    <style>
        body {
            background: #eef2f7;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 320px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: #004999;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            font-size: 14px;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <form method="POST">
            <h2>Admin Login</h2>
            <?= $message ?>
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
