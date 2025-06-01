<?php
$conn = new mysqli("localhost", "root", "", "voting_system");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $party = $_POST['party'];
    $photo = $_FILES['photo']['name'];
    $temp = $_FILES['photo']['tmp_name'];

    move_uploaded_file($temp, "../uploads/$photo");

    $stmt = $conn->prepare("INSERT INTO candidates (name, party, photo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $party, $photo);
    $stmt->execute();
}

$candidates = $conn->query("SELECT * FROM candidates");

// Start output with styles
echo "<div style='
    font-family: Arial, sans-serif;
    max-width: 700px;
    margin: 30px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background: #f9f9f9;
'>
<h2 style='text-align:center; color:#2c3e50;'>🗳️ Candidate List</h2>";

while ($row = $candidates->fetch_assoc()) {
    echo "<div style='
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    '>
        <div style='display: flex; align-items: center;'>
            <img src='../uploads/{$row['photo']}' width='60' height='60' style='border-radius: 50%; margin-right: 15px; border: 2px solid #007bff;'>
            <div>
                <strong style='font-size: 16px;'>{$row['name']}</strong><br>
                <span style='color: #555;'>Party: {$row['party']}</span>
            </div>
        </div>
        <form method='POST' action='delete_candidate.php' style='margin: 0;'>
            <input type='hidden' name='id' value='{$row['id']}'>
            <button type='submit' style='
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 6px 12px;
                border-radius: 5px;
                cursor: pointer;
            '>Delete</button>
        </form>
    </div>";
}

echo "</div>";

// Add candidate form
echo "<div style='
    font-family: Arial, sans-serif;
    max-width: 700px;
    margin: 30px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background: #ffffff;
'>
<h3 style='color:#2e7d32;'>➕ Add New Candidate</h3>
<form method='POST' enctype='multipart/form-data'>
    <input type='text' name='name' placeholder='Candidate Name' required style='
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    '><br>
    <input type='text' name='party' placeholder='Party Name' required style='
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    '><br>
    <input type='file' name='photo' required style='
        margin-bottom: 15px;
    '><br>
    <button type='submit' style='
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    '>Add Candidate</button>
</form>
</div>";
?>
