<?php
$conn = new mysqli("localhost", "root", "", "voting_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $conn->query("DELETE FROM candidates WHERE id=$id");
    header("Location: manage_candidates.php");
}
?>
