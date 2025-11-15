<?php
include 'connect.php';

// Check if the Vehicle Registration Number is set in the GET request
if (isset($_GET['Vehicle_RegNum'])) {
    $originalVehicleRegNum = $_GET['Vehicle_RegNum'];

    // Fetch the current vehicle data
    $sql = "SELECT * FROM vehicle WHERE Vehicle_RegNum = ?";
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $originalVehicleRegNum);
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $policyHolderID = $_POST['policyholder_id'];
    $newVehicleRegNum = $_POST['Vehicle_RegNum'];
    $type = $_POST['type'];
    $brand = $_POST['brand'];
    $manufactureYear = $_POST['manufacture_year'];
    $mileage = $_POST['mileage'];
    $purchaseDate = $_POST['purchase_date'];
    $engineID = $_POST['engine_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the UPDATE statement
        $sql = "UPDATE vehicle SET PolicyHolder_ID = ?, Vehicle_Type = ?, Vehicle_Brand = ?, Vehicle_ManufactureYear = ?, Vehicle_Mileage_KM = ?, Vehicle_PuchaseDate = ?, Vehicle_EngineID = ?, Vehicle_RegNum = ? WHERE Vehicle_RegNum = ?";
        
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("sssssssss", $policyHolderID, $type, $brand, $manufactureYear, $mileage, $purchaseDate, $engineID, $newVehicleRegNum, $originalVehicleRegNum);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error updating vehicle: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        echo "<div style='text-align: center; padding: 10px; background-color:rgb(46, 171, 229); color:rgb(98, 201, 224); font-weight: bold; border: 1px solidrgb(195, 213, 230);'>
                &#10004; Vehicle updated successfully.
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
    <title>Update Vehicle</title>
    <link rel="stylesheet" href="styleform.css">
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: rgb(37, 119, 185);
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="date"], input[type="number"], input[type="radio"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: rgb(37, 119, 185);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
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
    <section>
        <h2>Update Vehicle Data</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?Vehicle_RegNum=' . htmlspecialchars($originalVehicleRegNum); ?>" method="post">
            <label for="policyholder_id">Policy Holder ID:</label>
            <input type="text" name="policyholder_id" value="<?php echo htmlspecialchars($vehicle['PolicyHolder_ID']); ?>" required>

            <label for="Vehicle_RegNum">Vehicle Registration Number:</label>
            <input type="text" name="Vehicle_RegNum" value="<?php echo htmlspecialchars($vehicle['Vehicle_RegNum']); ?>" required>

            <label for="type">Vehicle Type:</label>
            <input type="text" name="type" value="<?php echo htmlspecialchars($vehicle['Vehicle_Type']); ?>" required>

            <label for="brand">Vehicle Brand:</label>
            <input type="text" name="brand" value="<?php echo htmlspecialchars($vehicle['Vehicle_Brand']); ?>" required>

            <label for="manufacture_year">Manufacture Year:</label>
            <input type="text" name="manufacture_year" value="<?php echo htmlspecialchars($vehicle['Vehicle_ManufactureYear']); ?>" required>

            <label for="mileage">Mileage (KM):</label>
            <input type="number" name="mileage" value="<?php echo htmlspecialchars($vehicle['Vehicle_Mileage_KM']); ?>" required>

            <label for="purchase_date">Purchase Date:</label>
            <input type="date" name="purchase_date" value="<?php echo htmlspecialchars($vehicle['Vehicle_PuchaseDate']); ?>" required>

            <label for="engine_id">Engine ID:</label>
            <input type="text" name="engine_id" value="<?php echo htmlspecialchars($vehicle['Vehicle_EngineID']); ?>" required>

            <input type="submit" value="Update Vehicle">
        </form>
    </section>
</body>
</html>