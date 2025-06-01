<?php
$conn = new mysqli("localhost", "root", "", "voting_system");
$res = $conn->query("SELECT * FROM candidates ORDER BY votes DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #222;
            margin-bottom: 30px;
        }

        table {
            width: 90%;
            max-width: 1000px;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        td strong {
            color: #333;
        }
    </style>
</head>
<body>

<h2>Election Results</h2>

<table>
    <tr>
        <th>Photo</th>
        <th>Name</th>
        <th>Party</th>
        <th>Votes</th>
    </tr>

    <?php
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
                <td><img src='../uploads/{$row['photo']}' alt='{$row['name']}'></td>
                <td><strong>{$row['name']}</strong></td>
                <td>{$row['party']}</td>
                <td>{$row['votes']}</td>
              </tr>";
    }
    ?>

</table>

</body>
</html>
