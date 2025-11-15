<?php
include 'connect.php';

// Check if a filter is applied
$filter_month = isset($_GET['month']) ? $_GET['month'] : '';

// SQL query to fetch data
$sql = "SELECT * FROM bill";
if (!empty($filter_month)) {
    $sql .= " WHERE MONTH(Transaction_Date) = '$filter_month'";
}

$result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="menu_style.css">
<head>
    <meta charset="UTF-8">
    <title>Bills</title>
    <style>
        table {
            margin: 0 auto;
            font-size: large;
            border: 1px solid black;
        }

        h1 {
            text-align: center;
            color: #006600;
            font-size: xx-large;
            font-family: 'Gill Sans', 'Gill Sans MT',
            'Calibri', 'Trebuchet MS', 'sans-serif';
        }

        td {
            background-color: #E4F5D4;
            border: 1px solid black;
        }

        th,
        td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        td {
            font-weight: lighter;
        }

        .filter {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<header>
        <a href="./menu.php">
            <img src="logo.png" alt="Logo" class="logo">
        </a>
    </header>
    <section>
        <h1>Bills</h1>

        <!-- Dropdown for filtering -->
        <div class="filter">
            <form method="GET" action="">
                <label for="month">Filter by Month:</label>
                <select name="month" id="month">
                    <option value="">All</option>
                    <option value="1" <?php if ($filter_month == '1') echo 'selected'; ?>>January</option>
                    <option value="2" <?php if ($filter_month == '2') echo 'selected'; ?>>February</option>
                    <option value="3" <?php if ($filter_month == '3') echo 'selected'; ?>>March</option>
                    <option value="4" <?php if ($filter_month == '4') echo 'selected'; ?>>April</option>
                    <option value="5" <?php if ($filter_month == '5') echo 'selected'; ?>>May</option>
                    <option value="6" <?php if ($filter_month == '6') echo 'selected'; ?>>June</option>
                    <option value="7" <?php if ($filter_month == '7') echo 'selected'; ?>>July</option>
                    <option value="8" <?php if ($filter_month == '8') echo 'selected'; ?>>August</option>
                    <option value="9" <?php if ($filter_month == '9') echo 'selected'; ?>>September</option>
                    <option value="10" <?php if ($filter_month == '10') echo 'selected'; ?>>October</option>
                    <option value="11" <?php if ($filter_month == '11') echo 'selected'; ?>>November</option>
                    <option value="12" <?php if ($filter_month == '12') echo 'selected'; ?>>December</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <table>
            <tr>
                <th>Bill ID</th>
                <th>Transaction Date</th>
                <th>Payment Method</th>
                <th>Payment Amount</th>
                <th>Policy_ID</th>
                <th>Actions</th>
            </tr>
            <?php
            // Loop to display data
            while ($rows = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $rows['Bill_ID']; ?></td>
                    <td><?php echo $rows['Transaction_Date']; ?></td>
                    <td><?php echo $rows['Payment_Method']; ?></td>
                    <td><?php echo $rows['Payment_Amount']; ?></td>
                    <td><?php echo $rows['Policy_ID']; ?></td>
                    <td><?php echo
                    "<a href='update_bills.php?Bill_ID=".$rows['Bill_ID']."'>
                    <button class='edit'>Edit</button></a>
                    <a href='delete_bills.php?Bill_ID=".$rows['Bill_ID']."'>
                    <button class='delete'>Delete</button></a>";
                ?>
            </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </section>
    <a href="add_bills.php" class="button add">ADD New Bills</a>
    <footer>
        <p>&copy; 2025 SafeDrive Insurance. All rights reserved.</p>
</body>

</html>
