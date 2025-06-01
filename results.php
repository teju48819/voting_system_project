<?php
include 'db.php';
date_default_timezone_set("Asia/Kolkata");
$today = date("Y-m-d");

// Fetch result date from DB
$setting = $conn->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();
$result_date = $setting['result_date'];

if ($today < $result_date) {
    echo "<div style='
            font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffecec;
            color: #b71c1c;
            padding: 15px;
            margin: 30px auto;
            max-width: 600px;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        '>
        ⛔ <strong>Results will be published on " . date("d M, Y", strtotime($result_date)) . "</strong>
        </div>";
    exit();
}

$res = $conn->query("SELECT * FROM candidates ORDER BY votes DESC");

echo "<div style='
        font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif;
        max-width: 700px;
        margin: 30px auto;
        padding: 10px;
    '>";

echo "<h2 style='
        text-align: center;
        color: #1a237e;
        border-bottom: 2px solid #1a237e;
        padding-bottom: 10px;
    '>📊 Government Election Voting System</h2>";

while ($row = $res->fetch_assoc()) {
    echo "<div style='
            background-color: #f0f4ff;
            margin: 15px 0;
            padding: 20px;
            border-left: 6px solid #3f51b5;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        ' onmouseover=\"this.style.transform='scale(1.02)'\" onmouseout=\"this.style.transform='scale(1)'\">
            <div style='font-size: 20px; font-weight: bold; color: #0d47a1;'>{$row['name']}</div>
            <div style='font-size: 16px; color: #555;'>Party: <strong>{$row['party']}</strong></div>
            <div style='font-size: 18px; color: #2e7d32; margin-top: 8px;'>🗳️ Votes: <strong>{$row['votes']}</strong></div>
        </div>";
}

echo "</div>";
?>
