<?php
include 'connect.php';

$sql = "SELECT * FROM claim";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) <0){
	echo "0 results";
}

mysqli_close ($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application List </title>
    <link rel="stylesheet" href="menu_style.css">
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
    </style>
</head>
 
<body>
<header>
        <a href="./menu.php">
            <img src="logo.png" alt="Logo" class="logo">
        </a>
    </header>
    <section>
        <h1>Claim List</h1>
        <table>
            <tr>
                <th>Claim ID</th>
                <th>Reason</th>
                <th>Amount</th>
				<th>Status</th>
				<th>Claim Date</th>
				<th>Application ID</th>
                <th>Actions</th>
            </tr>
            <?php 
                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
                <td><?php echo $rows['Claim_ID'];?></td>
                <td><?php echo $rows['Claim_Reason'];?></td>
                <td><?php echo $rows['Claim_Amount'];?></td>
                <td><?php echo $rows['Claim_Status'];?></td>
                <td><?php echo $rows['Claim_Date'];?></td>
				<td><?php echo $rows['Application_ID'];?></td>
                <td><?php echo
                    "<a href='update_claims.php?Claim_ID=".$rows['Claim_ID']."'>
                    <button class='edit'>Edit</button></a>
                    <a href='delete_claims.php?Claim_ID=".$rows['Claim_ID']."'>
                    <button class='delete'>Delete</button></a>";
                ?>
            </td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
    <a href="add_claims.php" class="button add">ADD New Claims</a>
    <footer>
        <p>&copy; 2025 SafeDrive Insurance. All rights reserved.</p>
</body>
 
</html>