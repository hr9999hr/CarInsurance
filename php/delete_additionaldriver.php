<?php
include 'connect.php';

// Check if the Driver ID is set in the GET request
if (isset($_GET['Driver_ID'])) {
    $driverID = $_GET['Driver_ID'];

    // Fetch the current driver data to confirm deletion
    $sql = "SELECT a.*, d.Driver_FirstName, d.Driver_LastName
            FROM additionaldriver a
            JOIN driver_details d ON a.Driver_NRIC = d.Driver_NRIC
            WHERE a.Driver_ID = ?";
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $driverID);
        $P->execute();
        $result = $P->get_result();

        // Check if the driver exists
        if ($result->num_rows > 0) {
            $driver = $result->fetch_assoc();
        } else {
            echo "<p>No driver found with the given ID.</p>";
            exit;
        }

        $P->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
} else {
    echo "<p>No driver ID provided.</p>";
    exit;
}

// Check if the deletion is confirmed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the DELETE statement
        $sql = "DELETE FROM additionaldriver WHERE Driver_ID = ?";
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("s", $driverID);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error deleting additional driver: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Additional driver deleted successfully!</p>";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Additional Driver</title>
    <link rel="stylesheet" href="styleform.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        h1 {
            color: #006600;
        }
        .button {
            background-color: #f44336; /* Red */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>
<header>
    <h1>Welcome to Safedrive Insurance</h1>
</header>

<nav>
    <div class="nav-links">
        <a href="home.php">Home</a>
    </div>
    <a href="additional_driver.php" class="back-button">Back to Additional Driver List</a>
</nav>
    <h2>Delete Additional Driver</h2>
    <p>Are you sure you want to delete the following driver?</p>
    <p><strong>Driver ID:</strong> <?php echo htmlspecialchars($driver['Driver_ID']); ?></p>
    <p><strong>First Name:</strong> <?php echo htmlspecialchars($driver['Driver_FirstName']); ?></p>
    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($driver['Driver_LastName']); ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?Driver_ID=' . htmlspecialchars($driverID); ?>" method="post">
        <input type="submit" class="button" value="Confirm Deletion">
    </form>
    <a href="additional_driver.php" class="button">Cancel</a>
</body>
</html>