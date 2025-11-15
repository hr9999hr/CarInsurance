<?php
include 'connect.php';

$sql = "SELECT * FROM commercial";

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
    <title>Commercial List</title>
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
        <h1>Commercial Vehicle</h1>
        <table>
            <tr>
                <th>Policy ID</th>
                <th>Company Registration Number</th>
                <th>Company Name</th>
                <th>Company Registration Date</th>
                <th>Company Address</th>
                <th>Company Phone Number</th>
                <th>Company Email Address</th>
				<th>Company Bank Name</th>
				<th>Company Bank Account Number</th>
            </tr>
            <?php 
                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH ROW OF EVERY COLUMN -->
                <td><?php echo $rows['Policy_ID'];?></td>
                <td><?php echo $rows['CompRegNum'];?></td>
                <td><?php echo $rows['CompName'];?></td>
                <td><?php echo $rows['CompRegDate'];?></td>
                <td><?php echo $rows['CompAddress'];?></td>
                <td><?php echo $rows['CompPhoneNum'];?></td>
				<td><?php echo $rows['CompEmailAddress'];?></td>
				<td><?php echo $rows['CompBankName'];?></td>
				<td><?php echo $rows['CompBankAccNum'];?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
</body> 
</html>