
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];
$user = $conn->query("SELECT name, has_voted FROM users WHERE id = $user_id")->fetch_assoc();

$message = "";
$show_thankyou = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = trim($_POST['feedback']);
    $rating = (int)$_POST['rating'];
    
    if ($rating < 1 || $rating > 5) {
        $message = "<div class='alert alert-danger'>Please select a valid rating</div>";
    } elseif (empty($feedback)) {
        $message = "<div class='alert alert-danger'>Please enter your feedback</div>";
    } else {
        $feedback = $conn->real_escape_string($feedback);
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, rating, comments) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $rating, $feedback);
        
        if ($stmt->execute()) {
            $show_thankyou = true;
        } else {
            $message = "<div class='alert alert-danger'>Error submitting feedback</div>";
        }
    }
}

// Check if coming from voting
$new_voter = isset($_GET['new_voter']) && $_GET['new_voter'] == 1;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback | Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rating {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .rating input { display: none; }
        .rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: all 0.2s;
        }
        .rating input:checked ~ label { color: #ffc107; }
        .rating label:hover,
        .rating label:hover ~ label { color: #ffc107; }
        .thank-you {
            text-align: center;
            padding: 50px;
        }
        .thank-you h2 { color: #28a745; }
    </style>
</head>
<body>
    <div class="container py-5">
        <?php if ($show_thankyou): ?>
            <div class="thank-you">
                <h2>Thank You!</h2>
                <p>Your feedback has been submitted successfully.</p>
                <a href="index.html" class="btn btn-primary">Return Home</a>
            </div>
        <?php else: ?>
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Feedback Form</h3>
                </div>
                <div class="card-body">
                    <?php if ($new_voter): ?>
                        <div class="alert alert-success mb-4">
                            <h4>Thank you for voting!</h4>
                            <p>We'd appreciate your feedback on the voting experience.</p>
                        </div>
                    <?php endif; ?>
                    
                    <?= $message ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">How would you rate your voting experience?</label>
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                                <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                                <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                                <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                                <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Your Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
