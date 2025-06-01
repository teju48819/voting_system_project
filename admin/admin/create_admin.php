<?php
$conn = new mysqli("localhost", "root", "", "voting_system");

$username = "admin";
$password = password_hash("admin123", PASSWORD_BCRYPT);

$sql = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";

if ($conn->query($sql)) {
    echo "✅ Admin created successfully!";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
