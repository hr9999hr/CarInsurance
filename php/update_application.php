<?php
include 'connect.php';

// Fetch vehicle registration numbers
$vehicleRegNums = [];
$sql = "SELECT Vehicle_RegNum FROM vehicle";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $vehicleRegNums[] = $row['Vehicle_RegNum'];
    }
    $result->free();
} else {
    echo "<p>Error fetching vehicle registration numbers: " . $conn->error . "</p>";
    exit;
}

// Check if the Application ID is set in the GET request
if (isset($_GET['Application_ID'])) {
    $applicationID = $_GET['Application_ID'];

    // Fetch the current application data
    $sql = "SELECT * FROM application WHERE Application_ID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $applicationID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the application exists
        if ($result->num_rows > 0) {
            $application = $result->fetch_assoc();
        } else {
            echo "<p>No application found with the given ID.</p>";
            exit;
        }

        $stmt->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
} else {
    echo "<p>No application ID provided.</p>";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $applicationDate = $_POST['application_date'];
    $applicationStatus = $_POST['application_status'];
    $policyID = $_POST['policy_id'];
    $vehicleRegNum = $_POST['vehicle_reg_num'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the UPDATE statement
        $sql = "UPDATE application SET Application_Date=?, Application_Status=?, Policy_ID=?, Vehicle_RegNum=? WHERE Application_ID=?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $applicationDate, $applicationStatus, $policyID, $vehicleRegNum, $applicationID);
            
            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Error updating application: " . $stmt->error);
            }
            
            // Close the statement
            $stmt->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Application updated successfully!</p>";
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
    <title>Edit Application</title>
    <link rel="stylesheet" href="styleform.css">
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #006600;
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
            background-color: #4CAF50; /* Green */
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
    <a href="application.php" class="back-button">Back to Application List</a>
</nav>
    <section>
        <h1>Edit Application</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?Application_ID=' . htmlspecialchars($applicationID); ?>" method="post">
            <label for="application_date">Application Date:</label>
            <input type="date" id="application_date" name="application_date" value="<?php echo htmlspecialchars($application['Application_Date']); ?>" required>

            <label for="application_status">Application Status:</label>
            <input type="text" id="application_status" name="application_status" value="<?php echo htmlspecialchars($application['Application_Status']); ?>" required>

            <label for="policy_id">Policy ID:</label>
            <input type="text" id="policy_id" name="policy_id" value="<?php echo htmlspecialchars($application['Policy_ID']); ?>" required>

            <label for="vehicle_reg_num">Vehicle Registration Number:</label>
            <select id="vehicle_reg_num" name="vehicle_reg_num" required>
                <?php foreach ($vehicleRegNums as $regNum): ?>
                    <option value="<?php echo htmlspecialchars($regNum); ?>" <?php echo ($application['Vehicle_RegNum'] == $regNum) ? 'selected' : ''; ?>><?php echo htmlspecialchars($regNum); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Update Application">
        </form>
    </section>
</body>
</html>