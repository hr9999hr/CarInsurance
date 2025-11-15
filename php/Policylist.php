<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Policy List</title>
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
        <h1>Policy List</h1>

        <?php
include 'connect.php';

$sql = "SELECT * FROM policy";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) <0){
	echo "0 results";
}else{
    echo '<table>';
    echo '<tr>
        <th>Policy ID</th>
        <th>Info ID</th>
        <th>Policy Start</th>
        <th>Policy End</th>
        <th>Type</th>
        <th>Purpose</th>
        <th>Actions</th>
        </tr>';

// Fetch and display the data
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['Policy_ID']) . '</td>';
    echo '<td>' . htmlspecialchars($row['Info_ID']) . '</td>';
    echo '<td>' . htmlspecialchars($row['Policy_Start']) . '</td>';
    echo '<td>' . htmlspecialchars($row['Policy_END']) . '</td>';
    echo '<td>' . htmlspecialchars($row['Policy_Type']) . '</td>';
    echo '<td>' . htmlspecialchars($row['Policy_Purpose']) . '</td>';
    echo '<td>

            <form action="view_policy.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="' . htmlspecialchars($row['Policy_ID']) . '">
                <button type="submit" class="button view">VIEW</button>
            </form>
            <form action="update_Policy.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="' . htmlspecialchars($row['Policy_ID']) . '">
                <button type="submit" class="button edit">EDIT</button>
            </form>
            <form action="delete_Policy.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="' . htmlspecialchars($row['Policy_ID']) . '">
                <button type="submit" class="button delete" onclick="return confirm(\'Are you sure you want to delete this policyholder?\');">DELETE</button>
            </form>
          </td>';
    echo '</tr>';
}
echo '</table>';
}    
mysqli_close ($conn);
?>
    <a href="add_Policy.php" class="button add">ADD New Policy </a>
    <footer>
        <p>&copy; 2025 SafeDrive Insurance. All rights reserved.</p>
    </footer>
</body>
</html>