<?php
session_start();
$conn = new mysqli("localhost", "root", "", "voting_system");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Fetch user from DB
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $res->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        // ✅ Store user ID and voting status in session
        $_SESSION['userid'] = $user['id'];
        $_SESSION['has_voted'] = $user['has_voted']; // <-- NEW
        $_SESSION['voter_id'] = $user['voter_id'];   // Optional: for vote.php

        header("Location: dashboard.php");
        exit();
    } else {
        $message = "<div class='message error'>❌ Invalid login credentials.</div>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login | Online Voting System</title>
    <style>
        body {
            background: #f0f4f8;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 300px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        form button:hover {
            background: #218838;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
        }

        .home-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #0066cc;
            text-decoration: none;
        }

        .home-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Login</h2>
    <?= $message ?>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <a class="home-link" href="index.html">🏠 Back to Home</a>
</form>

</body>
</html>
