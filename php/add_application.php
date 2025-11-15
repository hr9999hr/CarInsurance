<?php
include 'connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $applicationid = $_POST['applicationid'];
    $applicationDate = $_POST['application_date'];
    $applicationStatus = $_POST['application_status'];
    $policyID = $_POST['policy_id']; // User-defined Policy ID
    $vehicleRegNum = $_POST['vehicle_reg_num'];

    // Check if the Policy_ID exists
    $checkSql = "SELECT COUNT(*) FROM policy WHERE Policy_ID = ?";
    $checkP = $conn->prepare($checkSql);
    $checkP->bind_param("s", $policyID);
    $checkP->execute();
    $checkP->bind_result($count);
    $checkP->fetch();
    $checkP->close();

    if ($count == 0) {
        echo "<p>Error: The selected Policy ID does not exist. Please create the policy first.</p>";
    } else {
        // Prepare the INSERT statement for the application
        $sql = "INSERT INTO application (Application_ID, Application_Date, Application_Status, Policy_ID, Vehicle_RegNum) VALUES (?, ?, ?, ?, ?)";
        
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("sssss", $applicationid, $applicationDate, $applicationStatus, $policyID, $vehicleRegNum);
            
            // Execute the statement
            if ($P->execute()) {
                echo "<p>New application added successfully!</p>";
            } else {
                echo "<p>Error: " . $P->error . "</p>";
            }
            
            // Close the statement
            $P-> close();
        } else {
            echo "<p>Error preparing statement: " . $conn->error . "</p>";
        }
    }
}

mysqli_close($conn);
?>

<!-- HTML code for the Add Application Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Application</title>
    <link rel="stylesheet" href="styleform.css">
</head> 
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
<body>
<nav>
    <div class="nav-links">
        <a href="home.php">Home</a>
    </div>
    <a href="application.php" class="back-button">Back to Application List</a>
</nav>
    <section>
        <h1>Add New Application</h1>
        <form action="" method="POST">

        <label for="applicationid">Application ID:</label>
        <input type="text" name="applicationid" required><br>

            <label for="application_date">Application Date:</label>
            <input type="date" name="application_date" required><br>

            <label for="application_status">Application Status:</label>
            <input type="text" name="application_status" required><br>

            <label for="policy_id">Policy ID:</label>
            <input type="text" name="policy_id" required><br>

            <label for="vehicle_reg_num">Vehicle Registration Number:</label>
            <input type="text" name="vehicle_reg_num" required><br>

            <button type="submit">Add Application</button>
        </form>
    </section>
</body>
</html>