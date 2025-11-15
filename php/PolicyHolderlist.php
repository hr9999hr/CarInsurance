<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Policy Holder List</title>
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
        <h1>Policy Holder List</h1>

        <?php
include 'connect.php';

$sql = "SELECT p.*, d.PolicyHolder_FirstName, d.PolicyHolder_LastName, d.PolicyHolder_Gender, d.PolicyHolder_NRIC, d.PolicyHolder_Nationality, a.PolicyHolder_Age, d.PolicyHolder_DOB
        FROM policyholder p 
        JOIN policyholder_details d ON p.PolicyHolder_NRIC = d.PolicyHolder_NRIC 
        JOIN policyholder_agedetail a ON d.PolicyHolder_DOB = a.PolicyHolder_DOB";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) <0){
	echo "0 results";
}else{
    echo '<table>';
    echo '<tr>
        <th>PolicyHolder ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Gender</th>
        <th>IC Number</th>
        <th>Date of Birth</th>
        <th>Age</th>
        <th>Nationality</th>
        <th>Marital Status</th>
        <th>Occupation</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Address</th>
        <th>License Number</th>
        <th>Amount Due</th>
        <th>Actions</th>
        </tr>';

// Fetch and display the data
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_ID']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_FirstName']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_LastName']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_Gender']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_NRIC']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_DOB']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_Age']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_Nationality']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_MaritalStatus']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_Occupation']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_PhoneNum']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_Email']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_Address']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_LicenseNum']) . '</td>';
    echo '<td>' . htmlspecialchars($row['PolicyHolder_AmountDue']) . '</td>';
    echo '<td>
            <form action="update_PolicyHolder.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="' . htmlspecialchars($row['PolicyHolder_ID']) . '">
                <button type="submit" class="button edit">EDIT</button>
            </form>
            <form action="delete_PolicyHolder.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="' . htmlspecialchars($row['PolicyHolder_ID']) . '">
                <button type="submit" class="button delete" onclick="return confirm(\'Are you sure you want to delete this policyholder?\');">DELETE</button>
            </form>
          </td>';
    echo '</tr>';
}
echo '</table>';
}    
mysqli_close ($conn);
?>
    <a href="add_PolicyHolder.php" class="button add">ADD New Policy Holder</a>
    <footer>
        <p>&copy; 2025 SafeDrive Insurance. All rights reserved.</p>
    </footer>
</body>
</html>