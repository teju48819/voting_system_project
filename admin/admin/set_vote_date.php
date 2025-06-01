<?php
$conn = new mysqli("localhost", "root", "", "voting_system");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start = $_POST['voting_start'];
    $end = $_POST['voting_end'];
    $result = $_POST['result_date'];

    // Check if settings already exist
    $check = $conn->query("SELECT * FROM settings");
    if ($check->num_rows > 0) {
        // Update existing record
        $conn->query("UPDATE settings SET voting_start='$start', voting_end='$end', result_date='$result'");
        $message = "✅ Dates updated successfully!";
    } else {
        // Insert new record
        $conn->query("INSERT INTO settings (voting_start, voting_end, result_date) VALUES ('$start', '$end', '$result')");
        $message = "✅ Dates set successfully!";
    }
}

// Fetch existing dates to show in form
$res = $conn->query("SELECT * FROM settings LIMIT 1");
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Voting Dates</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f5f5f5;
            text-align: center;
            margin-top: 50px;
        }
        form {
            background: #fff;
            display: inline-block;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }
        input {
            margin: 10px;
            padding: 8px;
        }
        button {
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .message {
            margin-top: 10px;
            color: green;
        }
    </style>
</head>
<body>

<h2>🗓️ Set Voting and Result Dates</h2>

<form method="POST">
    <label>Voting Start Date:</label><br>
    <input type="date" name="voting_start" value="<?= $row['voting_start'] ?? '' ?>" required><br>

    <label>Voting End Date:</label><br>
    <input type="date" name="voting_end" value="<?= $row['voting_end'] ?? '' ?>" required><br>

    <label>Result Date:</label><br>
    <input type="date" name="result_date" value="<?= $row['result_date'] ?? '' ?>" required><br>

    <button type="submit">Save Dates</button>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>
</form>

</body>
</html>
