<?php
include 'connect.php';

// Fetch all PolicyHolder_IDs for the dropdown
$policyholders = [];
$query = "SELECT PolicyHolder_ID FROM policyholder";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $policyholders[] = $row['PolicyHolder_ID'];
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $policyHolderID = $_POST['policyholder_id'];
    $vehicleRegNum = $_POST['vehicle_reg_num'];
    $type = $_POST['type'];
    $brand = $_POST['brand'];
    $manufactureYear = $_POST['manufacture_year'];
    $mileage = $_POST['mileage'];
    $purchaseDate = $_POST['purchase_date'];
    $engineID = $_POST['engine_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the INSERT statement
        $sql = "INSERT INTO vehicle (PolicyHolder_ID, Vehicle_RegNum, Vehicle_Type, Vehicle_Brand, Vehicle_ManufactureYear, Vehicle_Mileage_KM, Vehicle_PuchaseDate, Vehicle_EngineID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("ssssssss", $policyHolderID, $vehicleRegNum, $type, $brand, $manufactureYear, $mileage, $purchaseDate, $engineID);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error inserting vehicle: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Vehicle added successfully!</p>";
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
    <title>Add Vehicle</title>
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
        input[type="text"], input[type="date"], input[type="number"], select {
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
        <h1>Add Vehicle Data</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="policyholder_id">Policy Holder ID:</label>
            <select id="policyholder_id" name="policyholder_id" required>
                <option value="">Select a Policy Holder ID</option>
                <?php foreach ($policyholders as $id): ?>
                    <option value="<?php echo ($id); ?>"><?php echo ($id); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="vehicle_reg_num">Vehicle Registration Number:</label>
            <input type="text" id="vehicle_reg_num" name="vehicle_reg_num" required>
            <label for="type">Vehicle Type:</label>
            <input type="text" id="type" name="type" required>

            <label for="brand">Vehicle Brand:</label>
            <input type="text" id="brand" name="brand" required>

            <label for="manufacture_year">Manufacture Year:</label>
            <input type="text" id="manufacture_year" name="manufacture_year" required>

            <label for="mileage">Mileage (KM):</label>
            <input type="number" id="mileage" name="mileage" required>

            <label for="purchase_date">Purchase Date:</label>
            <input type="date" id="purchase_date" name="purchase_date" required>

            <label for="engine_id">Engine ID :</label>
            <input type="text" id="engine_id" name="engine_id" required>

            <input type="submit" value="Add Vehicle">
        </form>
    </section>
</body>
</html>