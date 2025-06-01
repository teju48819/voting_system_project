
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "voting_system");

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];

// Check if user has already voted
$res = $conn->query("SELECT has_voted FROM users WHERE id = $user_id");
$row = $res->fetch_assoc();

if ($row['has_voted'] == 1) {
    header("Location: feedback.php");
    exit();
}

// Handle vote submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cid = $_POST['candidate'];

    // Transaction for data integrity
    $conn->begin_transaction();
    try {
        // Increment candidate vote count
        $conn->query("UPDATE candidates SET votes = votes + 1 WHERE id = $cid");
        
        // Mark user as voted
        $conn->query("UPDATE users SET has_voted = 1 WHERE id = $user_id");
        $_SESSION['has_voted'] = 1;
        
        $conn->commit();
        header("Location: feedback.php?new_voter=1");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error processing your vote. Please try again.";
    }
}

// Get candidates
$candidates = $conn->query("SELECT * FROM candidates");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vote | Online Voting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cast Your Vote</h2>
        <form method="POST">
            <?php while ($row = $candidates->fetch_assoc()) { ?>
                <div class="candidate-card">
                    <img src="uploads/<?= $row['photo'] ?>" width="100">
                    <h3><?= $row['name'] ?></h3>
                    <p><?= $row['party'] ?></p>
                    <input type="radio" name="candidate" value="<?= $row['id'] ?>" required>
                </div>
            <?php } ?>
            <button type="submit" class="btn-vote">Submit Vote</button>
        </form>
    </div>
</body>
</html>
