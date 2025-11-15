<?php
include 'connect.php';

$sql = "SELECT * FROM application";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) < 0) {
    echo "0 results";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application List</title>
    <link rel="stylesheet" href="menu_style.css">
    <style>
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
            ' Calibri', 'Trebuchet MS', 'sans-serif';
        }
 
        td {
            background-color: #E4F5D4;
            border: 1px solid black;
        }
 
        th,td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        td {
            font-weight: lighter;
        }
        .button {
            padding: 5px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .add {
            background-color: #4CAF50; /* Green */
            color: white;
            margin-left: auto;
        }
        .edit {
            background-color: #FFA500; /* Orange */
            color: white;
        }
        .delete {
            background-color: #f44336; /* Red */
            color: white;
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
        <h1>Application List</h1>
        <table>
            <tr>
                <th>Application ID</th>
                <th>Application Date</th>
                <th>Application Status</th>
                <th>Policy ID</th>
                <th>Vehicle Registration Number</th>
            </tr>
            <?php 
                // LOOP TILL END OF DATA
                while ($rows = $result->fetch_assoc()) {
            ?>
            <tr>
            <td><?php echo $rows['Application_ID']; ?></td>
            <td><?php echo $rows['Application_Date']; ?></td>
            <td><?php echo $rows['Application_Status']; ?></td>
            <td><?php echo $rows['Policy_ID']; ?></td>
            <td><?php echo $rows['Vehicle_RegNum']; ?></td>
            <td><?php echo
                    "<a href='update_application.php?Application_ID=".$rows['Application_ID']."'>
                    <button class='edit'>Edit</button></a>
                    <a href='delete_application.php?Application_ID=".$rows['Application_ID']."'>
                    <button class='delete'>Delete</button></a>";
                ?>
            </td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
    <a href="add_application.php" class="button add">ADD New Application</a>
    <footer>
        <p>&copy; 2025 SafeDrive Insurance. All rights reserved.</p>
    </footer>
</body>
</html>