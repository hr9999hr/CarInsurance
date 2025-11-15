<?php
include 'connect.php';

$sql = "SELECT a.*, d.Driver_FirstName, d.Driver_LastName, d.Driver_Gender, d.Driver_DOB
        FROM additionaldriver a 
        JOIN driver_details d ON a.Driver_NRIC = d.Driver_NRIC";

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
        <h1>Additional Driver List</h1>
        <table>
            <tr>
                <th>Driver ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>NRIC</th>
                <th>Date Of Birth</th>
                <th>Occupation</th>
				<th>Relationship to Applicant</th>
				<th>Driving Experience (Year)</th>
				<th>Type of Driving License</th>
                <th>Vehicle Registration Number</th>
                <th>Actions</th>
            </tr>
            <?php 
                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
            <tr>
                <td><?php echo $rows['Driver_ID'];?></td>
                <td><?php echo $rows['Driver_FirstName'];?></td>
                <td><?php echo $rows['Driver_LastName'];?></td>
                <td><?php echo $rows['Driver_Gender'];?></td>
                <td><?php echo $rows['Driver_NRIC'];?></td>
				<td><?php echo $rows['Driver_DOB'];?></td>
				<td><?php echo $rows['Driver_Occupation'];?></td>
				<td><?php echo $rows['Driver_RelationshipToApplicant'];?></td>
                <td><?php echo $rows['Driver_DrivingExperienceYear'];?></td>
                <td><?php echo $rows['Driver_TypeOfDrivingLicense'];?></td>
                <td><?php echo $rows['Vehicle_RegNum'];?></td>
                <td><?php echo
                    "<a href='update_additionaldriver.php?Driver_ID=".$rows['Driver_ID']."'>
                    <button class='edit'>Edit</button></a>
                    <a href='delete_additionaldriver.php?Driver_ID=".$rows['Driver_ID']."'>
                    <button class='delete'>Delete</button></a>";
                ?></td>
            <?php
                }
            ?>
        </table>
    </section>
    <a href="add_additionaldriver.php" class="button add">ADD New Driver</a>
    <footer>
        <p>&copy; 2025 SafeDrive Insurance. All rights reserved.</p>
    </footer>

</body>
 
</html>