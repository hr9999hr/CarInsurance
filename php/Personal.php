<?php
include 'connect.php';

$sql = "SELECT * FROM personal";

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
        <h1>Personal Vehicle</h1>
        <table>
            <tr>
                <th>Policy ID</th>
                <th>Bank Account Name</th>
                <th>Bank Account Number</th>
                <th>Occupation</th>
                <th>Number of Drivers</th>
                <th>Usage Details</th>
            </tr>
            <?php 
                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
            <tr>
                <td><?php echo $rows['Policy_ID'];?></td>
                <td><?php echo $rows['P_BankAccName'];?></td>
                <td><?php echo $rows['P_BankAccNum'];?></td>
                <td><?php echo $rows['P_Occupation'];?></td>
                <td><?php echo $rows['P_NumOfDrivers'];?></td>
                <td><?php echo $rows['P_Usage_Details'];?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
</body> 
</html>