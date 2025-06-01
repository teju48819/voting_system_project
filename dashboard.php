<?php
session_start();
$conn = new mysqli("localhost", "root", "", "voting_system");

$user_id = $_SESSION['userid'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

if ($user['has_voted']) {
    echo "You have already voted.";
    exit;
}

$candidates = $conn->query("SELECT * FROM candidates");

echo "<h2>Welcome, " . $user['name'] . "</h2>";
echo "<form method='POST' action='vote.php'>";
while ($row = $candidates->fetch_assoc()) {
    echo "<div>
        <img src='uploads/{$row['photo']}' width='100'><br>
        <input type='radio' name='candidate' value='{$row['id']}' required> {$row['name']} - {$row['party']}
    </div><br>";
}
echo "<button type='submit'>Vote</button>";
echo "</form>";
?>
