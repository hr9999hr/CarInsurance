<?php
include 'connect.php';

// SQL query to get the top 5 vehicle types insured based on count
$sql = "SELECT Vehicle_Type, COUNT(*) AS Vehicle_Count
        FROM vehicle
        GROUP BY Vehicle_Type
        ORDER BY Vehicle_Count DESC
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) <= 0) {
    echo "No results found.";
    mysqli_close($conn);
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Top 5 Vehicle Types</title>
    <link rel="stylesheet" href="menu_style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: rgb(37, 119, 185);
        }
        .button {
            padding: 5px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<header>
        <a href="./menu.php">
            <img src="logo.png" alt="Logo" class="logo">
        </a>
    </header>
<body>
    <section>
        <h1>Top 5 Vehicle Types Insured</h1>
        <table>
            <tr>
                <th>Vehicle Type</th>
                <th>Number of Policies</th>
            </tr>
            <?php 
                // Loop through the result and display data
                while ($rows = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $rows['Vehicle_Type']; ?></td>
                <td><?php echo $rows['Vehicle_Count']; ?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
</body>
</html>
