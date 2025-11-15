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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $driverid=$_POST['driverid'];
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
        // Prepare the INSERT statement for driver details
        $sqlDriver = "INSERT INTO driver_details (Driver_NRIC, Driver_FirstName, Driver_LastName, Driver_Gender, Driver_DOB) 
                      VALUES (?, ?, ?, ?, ?)";
        
        if ($PDriver = $conn->prepare($sqlDriver)) {
            $PDriver->bind_param("sssss", $nric, $firstName, $lastName, $gender, $dob);
            $PDriver->execute();
            $PDriver->close();
        } else {
            throw new Exception("Error preparing driver details statement: " . $conn->error);
        }

        // Prepare the INSERT statement for additional driver
        $sqlAdditionalDriver = "INSERT INTO additionaldriver (Driver_ID,Driver_NRIC, Driver_RelationshipToApplicant, Driver_DrivingExperienceYear, Driver_TypeOfDrivingLicense, Vehicle_RegNum, Driver_Occupation) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if ($PAdditionalDriver = $conn->prepare($sqlAdditionalDriver)) {
            $PAdditionalDriver->bind_param("sssssss",$driverid,$nric, $relationship, $drivingExperience, $licenseType, $vehicleRegNum, $occupation);
            $PAdditionalDriver->execute();
            $PAdditionalDriver->close();
        } else {
            throw new Exception("Error preparing additional driver statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Additional driver added successfully!</p>";
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
    <title>Add Additional Driver</title>
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
    <a href="additional_driver.php" class="back-button">Back to Additional Driver List</a>
</nav>
    <section>
        <h1>Add Additional Driver</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <label for="driverid">Driver ID:</label>
            <input type="text" id="driverid" name="driverid" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="nric">NRIC:</label>
            <input type="text" id="nric" name="nric" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>

            <label for="occupation">Occupation:</label>
            <input type="text" id="occupation" name="occupation" required>

            <label for="relationship">Relationship to Applicant:</label>
            <input type="text" id="relationship" name="relationship" required>

            <label for="driving_experience">Driving Experience (Years):</label>
            <input type="number" id="driving_experience" name="driving_experience" required>

            <label for="license_type">Type of Driving License:</label>
            <input type="text" id="license_type" name="license_type" required>

            <label for="vehicle_reg_num">Vehicle Registration Number:</label>
            <select id="vehicle_reg_num" name="vehicle_reg_num" required>
                <?php foreach ($vehicleRegNums as $regNum): ?>
                    <option value="<?php echo htmlspecialchars($regNum); ?>"><?php echo htmlspecialchars($regNum); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Add Driver">
        </form>
    </section>
</body>
</html>