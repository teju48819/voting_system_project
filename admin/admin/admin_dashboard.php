<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background: #eef;
            padding: 40px;
            text-align: center;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 0 10px gray;
        }
        h2 {
            margin-bottom: 20px;
        }
        a {
            display: block;
            margin: 10px;
            color: white;
            background: #5c67f2;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            width: 250px;
            margin-left: auto;
            margin-right: auto;
        }
        /* Style for the feedback icon */
        .feedback-icon {
            margin-right: 8px;
        }
    </style>
    <!-- Add Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin</h2>
        <a href="manage_candidates.php">Manage Candidates</a>
        <a href="view_results.php">View Results</a>
        <a href="set_voting_dates.php">Set Voting Dates</a>
        <a href="feedback_view.php">
           View Voter Feedback
        </a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>