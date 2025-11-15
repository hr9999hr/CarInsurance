<?php
include 'connect.php';

$sql = "SELECT * FROM claim_history";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) <0){
	echo "0 results";
}

mysqli_close ($conn);
?>

<!-- HTML code to display data in tabular format -->
<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <title>Application List </title>
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
    <section>
        <h1>Claim List</h1>
        <table>
            <tr>
                <th>Claim ID</th>
                <th>History Reason</th>
                <th>History Amount</th>
				<th>Claim Date</th>
            </tr>
            <?php 
                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH ROW OF EVERY COLUMN -->
                <td><?php echo $rows['Claim_ID'];?></td>
                <td><?php echo $rows['ClaimHistory_Reason'];?></td>
                <td><?php echo $rows['ClaimHistory_Amount'];?></td>
                <td><?php echo $rows['Claim_Date'];?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
</body> 
</html>