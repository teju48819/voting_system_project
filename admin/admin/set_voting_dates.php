<?php
session_start();
$conn = new mysqli("localhost", "root", "", "voting_system");

// Redirect if not admin
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start = $_POST['voting_start'];
    $end = $_POST['voting_end'];
    $result = $_POST['result_date'];

    $check = $conn->query("SELECT * FROM settings");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE settings SET voting_start='$start', voting_end='$end', result_date='$result'");
        $message = "✅ Voting dates updated successfully!";
    } else {
        $conn->query("INSERT INTO settings (voting_start, voting_end, result_date) VALUES ('$start', '$end', '$result')");
        $message = "✅ Voting dates set successfully!";
    }
}

// Get existing settings
$row = $conn->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Voting Dates</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f3f4f6;
            text-align: center;
            padding: 50px;
        }
        .box {
            background: #fff;
            padding: 25px 40px;
            display: inline-block;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="date"] {
            padding: 8px;
            margin: 10px;
            width: 80%;
            font-size: 16px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }
        .message {
            color: green;
            margin-top: 15px;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>🗳️ Set Voting and Result Dates</h2>

    <form method="POST">
        <label>Voting Start Date:</label><br>
        <input type="date" name="voting_start" value="<?= $row['voting_start'] ?? '' ?>" required><br>

        <label>Voting End Date:</label><br>
        <input type="date" name="voting_end" value="<?= $row['voting_end'] ?? '' ?>" required><br>

        <label>Result Declare Date:</label><br>
        <input type="date" name="result_date" value="<?= $row['result_date'] ?? '' ?>" required><br>

        <button type="submit">Save Dates</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>
</div>

</body>
</html>
