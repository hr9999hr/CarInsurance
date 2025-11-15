<?php
include 'connect.php';

$sql = "SELECT * FROM vehicle";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) <0){
	echo "0 results";
}

mysqli_close ($conn);
?>


<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="menu_style.css">
<head>
    <meta charset="UTF-8">
    <title>Application List </title>
    
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
        <h1>Vehicle List</h1>
        <table>
            <tr>
                <th>Policy Holder_ID</th>
                <th>Vehicle Registration Number</th>
                <th>Type</th>
				<th>Brand</th>
				<th>Manufacture Year</th>
				<th>Mileage(KM)</th>
				<th>Purchase Date</th>
				<th>Engine ID</th>
                <th>Action</th>
            </tr>
            <?php 
                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
            <tr>
                <td><?php echo $rows['PolicyHolder_ID'];?></td>
                <td><?php echo $rows['Vehicle_RegNum'];?></td>
                <td><?php echo $rows['Vehicle_Type'];?></td>
                <td><?php echo $rows['Vehicle_Brand'];?></td>
                <td><?php echo $rows['Vehicle_ManufactureYear'];?></td>
				<td><?php echo $rows['Vehicle_Mileage_KM'];?></td>
				<td><?php echo $rows['Vehicle_PuchaseDate'];?></td>
				<td><?php echo $rows['Vehicle_EngineID'];?></td>
                <td><?php echo
                    "<a href='update_vehicle.php?Vehicle_RegNum=".$rows['Vehicle_RegNum']."'>
                    <button class='edit'>Edit</button></a>
                    <a href='delete_vehicle.php?Vehicle_RegNum=".$rows['Vehicle_RegNum']."'>
                    <button class='delete'>Delete</button></a>";
                ?></td>
            <?php
                }
            ?>
        </table>
    </section>
    <a href="add_vehicle.php" class="button add">ADD New Vehicle</a>
    <footer>
        <p>&copy; 2025 SafeDrive Insurance. All rights reserved.</p>
    </footer>
</body> 
</html>