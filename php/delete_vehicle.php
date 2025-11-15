<?php
include 'connect.php';

// Check if the Vehicle Registration Number is set in the GET request
if (isset($_GET['Vehicle_RegNum'])) {
    $vehicleRegNum = $_GET['Vehicle_RegNum'];

    // Fetch the current vehicle data to confirm deletion
    $sql = "SELECT * FROM vehicle WHERE Vehicle_RegNum = ?";
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $vehicleRegNum);
        $P->execute();
        $result = $P->get_result();

        // Check if the vehicle exists
        if ($result->num_rows > 0) {
            $vehicle = $result->fetch_assoc();
        } else {
            echo "<p>No vehicle found with the given registration number.</p>";
            exit;
        }

        $P->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
} else {
    echo "<p>No vehicle registration number provided.</p>";
    exit;
}

// Check if the deletion is confirmed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the DELETE statement
        $sql = "DELETE FROM vehicle WHERE Vehicle_RegNum = ?";
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("s", $vehicleRegNum);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error deleting vehicle: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<div style='text-align: center; padding: 10px; background-color:rgb(46, 171, 229); color:rgb(146, 98, 224); font-weight: bold; border: 1px solidrgb(195, 213, 230);'>
                &#10004; Vehicle deleted successfully.
              </div>";
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
    <title>Delete Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        h1 {
            color: rgb(37, 119, 185);
        }
        .button {
            background-color: rgb(37, 119, 185);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<nav>
    <div class="nav-links">
        <a href="home.php">Home</a>
    </div>
    <a href="Vehiclelist.php" class="back-button">Back to Vehicle List</a>
</nav>
    <h1>Delete Vehicle</h1>
    <p>Are you sure you want to delete the following vehicle?</p>
    <p><strong>Vehicle Registration Number:</strong> <?php echo htmlspecialchars($vehicle['Vehicle_RegNum']); ?></p>
    <p><strong>Vehicle Type:</strong> <?php echo htmlspecialchars($vehicle['Vehicle_Type']); ?></p>
    <p><strong>Vehicle Brand:</strong> <?php echo htmlspecialchars($vehicle['Vehicle_Brand']); ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?Vehicle_RegNum=' . htmlspecialchars($vehicleRegNum); ?>" method="post">
        <input type="submit" class="button" value="Confirm Deletion">
    </form>
    <a href="Vehiclelist.php" class="button">Cancel</a>
</body>
</html>