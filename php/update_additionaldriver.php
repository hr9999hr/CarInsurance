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

// Check if the Driver ID is set in the GET request
if (isset($_GET['Driver_ID'])) {
    $driverID = $_GET['Driver_ID'];

    // Fetch the current driver data
    $sql = "SELECT a.*, d.Driver_FirstName, d.Driver_LastName, d.Driver_Gender, d.Driver_DOB
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $driverid = $_POST['driverid'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $gender = $_POST['gender'];
    $nric = $_POST['nric'];
    $dob = $_POST['dob'];
    $occupation = $_POST['occupation'];
    $relationship = $_POST['relationship'];
    $drivingExperience = $_POST['driving_experience'];
    $licenseType = $_POST['license_type'];
    $vehicleRegNum = $_POST['vehicle_reg_num'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the UPDATE statement for driver details
        $sqlDriver = "UPDATE driver_details SET Driver_FirstName=?, Driver_LastName=?, Driver_Gender=?, Driver_DOB=? WHERE Driver_NRIC=?";
        
        if ($PDriver = $conn->prepare($sqlDriver)) {
            $PDriver->bind_param("sssss", $firstName, $lastName, $gender, $dob, $nric);
            $PDriver->execute();
            $PDriver->close();
        } else {
            throw new Exception("Error preparing driver details statement: " . $conn->error);
        }

        // Prepare the UPDATE statement for additional driver
        $sqlAdditionalDriver = "UPDATE additionaldriver SET Driver_RelationshipToApplicant=?, Driver_DrivingExperienceYear=?, Driver_TypeOfDrivingLicense=?, Vehicle_RegNum=?, Driver_Occupation=? WHERE Driver_ID=?";
        
        if ($PAdditionalDriver = $conn->prepare($sqlAdditionalDriver)) {
            $PAdditionalDriver->bind_param("ssssss", $relationship, $drivingExperience, $licenseType, $vehicleRegNum, $occupation, $driverid);
            $PAdditionalDriver->execute();
            $PAdditionalDriver->close();
        } else {
            throw new Exception("Error preparing additional driver statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Additional driver updated successfully!</p>";
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
    <title>Update Additional Driver</title>
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
            background-color: #FFA500; /* Orange */
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #e68a00;
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
    <section>
        <h1>Update Additional Driver</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?Driver_ID=' . htmlspecialchars($driverID); ?>" method="post">
            <label for="driverid">Driver ID:</label>
            <input type="text" id="driverid" name="driverid" value="<?php echo htmlspecialchars($driver['Driver_ID']); ?>" readonly required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($driver['Driver_FirstName']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($driver['Driver_LastName']); ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php echo ($driver['Driver_Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($driver['Driver_Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($driver['Driver_Gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>

            <label for="nric">NRIC:</label>
            <input type="text" id="nric" name="nric" value="<?php echo htmlspecialchars($driver['Driver_NRIC']); ?>" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($driver['Driver_DOB']); ?>" required>

            <label for="occupation">Occupation:</label>
            <input type="text" id="occupation" name="occupation" value="<?php echo htmlspecialchars($driver['Driver_Occupation']); ?>" required>

            <label for="relationship">Relationship to Applicant:</label>
            <input type="text" id="relationship" name="relationship" value="<?php echo htmlspecialchars($driver['Driver_RelationshipToApplicant']); ?>" required>

            <label for="driving_experience">Driving Experience (Years):</label>
            <input type="number" id="driving_experience" name="driving_experience" value="<?php echo htmlspecialchars($driver['Driver_DrivingExperienceYear']); ?>" required>

            <label for="license_type">Type of Driving License:</label>
            <input type="text" id="license_type" name="license_type" value="<?php echo htmlspecialchars($driver['Driver_TypeOfDrivingLicense']); ?>" required>

            <label for="vehicle_reg_num">Vehicle Registration Number:</label>
            <select id="vehicle_reg_num" name="vehicle_reg_num" required>
                <?php foreach ($vehicleRegNums as $regNum): ?>
                    <option value="<?php echo htmlspecialchars($regNum); ?>" <?php echo ($driver['Vehicle_RegNum'] == $regNum) ? 'selected' : ''; ?>><?php echo htmlspecialchars($regNum); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Update Driver">
        </form>
    </section>
</body>
</html>