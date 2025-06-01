<?php
session_start();
require '../db.php'; // Make sure this path is correct

// Check admin authentication
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Pagination configuration
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $per_page;

// Get total count of feedback
$total_query = "SELECT COUNT(*) FROM feedback";
$total_result = $conn->query($total_query);
$total_feedback = $total_result->fetch_row()[0];
$total_pages = ceil($total_feedback / $per_page);

// Get feedback data with user information
$query = "SELECT f.id, f.rating, f.comments, f.created_at, 
                 u.id as user_id, u.name as user_name, u.email
          FROM feedback f
          JOIN users u ON f.user_id = u.id
          ORDER BY f.created_at DESC
          LIMIT $start, $per_page";

$feedback_result = $conn->query($query);

// Calculate average rating
$avg_rating_query = "SELECT AVG(rating) FROM feedback";
$avg_rating_result = $conn->query($avg_rating_query);
$avg_rating = $avg_rating_result->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .rating-stars { color: #ffc107; }
        .card-header { background-color: #343a40; color: white; }
        .sidebar { min-height: 100vh; background-color: #343a40; color: white; }
        .nav-link { color: rgba(255,255,255,.75); }
        .nav-link:hover, .nav-link.active { color: white; }
        .stats-card { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <!-- <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_candidates.php">
                                <i class="bi bi-people"></i> Manage Candidates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="feedback_view.php">
                                <i class="bi bi-chat-square-text"></i> Voter Feedback
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_results.php">
                                <i class="bi bi-bar-chart"></i> View Results
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div> -->

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h2 class="h2 mb-4">Voter Feedback Management</h2>
                
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary stats-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Feedback</h5>
                                <p class="card-text display-6"><?= $total_feedback ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success stats-card">
                            <div class="card-body">
                                <h5 class="card-title">Average Rating</h5>
                                <p class="card-text display-6"><?= number_format($avg_rating, 1) ?>/5</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Feedback List</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($feedback_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Rating</th>
                                            <th>Feedback</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = $feedback_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['id'] ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($row['user_name']) ?></strong><br>
                                                    <small><?= htmlspecialchars($row['email']) ?></small>
                                                </td>
                                                <td>
                                                    <?= str_repeat('<i class="bi bi-star-fill rating-stars"></i>', $row['rating']) ?>
                                                    <?= str_repeat('<i class="bi bi-star rating-stars"></i>', 5 - $row['rating']) ?>
                                                    (<?= $row['rating'] ?>/5)
                                                </td>
                                                <td><?= nl2br(htmlspecialchars($row['comments'])) ?></td>
                                                <td><?= date('M d, Y h:i A', strtotime($row['created_at'])) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <nav aria-label="Feedback pagination">
                                <ul class="pagination justify-content-center">
                                    <?php if($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page-1 ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page+1 ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php else: ?>
                            <div class="alert alert-info">
                                No feedback has been submitted yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>