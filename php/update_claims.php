<?php
include 'connect.php';

// Fetch application IDs for the dropdown
$applicationIDs = [];
$sql = "SELECT Application_ID FROM application";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $applicationIDs[] = $row['Application_ID'];
    }
    $result->free();
} else {
    echo "<p>Error fetching application IDs: " . $conn->error . "</p>";
    exit;
}

// Check if the Claim ID is set in the GET request
if (isset($_GET['Claim_ID'])) {
    $claimID = $_GET['Claim_ID'];

    // Fetch the current claim data
    $sql = "SELECT * FROM claim WHERE Claim_ID = ?";
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $claimID);
        $P->execute();
        $result = $P->get_result();

        // Check if the claim exists
        if ($result->num_rows > 0) {
            $claim = $result->fetch_assoc();
        } else {
            echo "<p>No claim found with the given ID.</p>";
            exit;
        }

        $P->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
} else {
    echo "<p>No claim ID provided.</p>";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $claimReason = $_POST['claim_reason'];
    $claimAmount = $_POST['claim_amount'];
    $claimStatus = $_POST['claim_status'];
    $claimDate = $_POST['claim_date'];
    $applicationID = $_POST['application_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the UPDATE statement
        $sql = "UPDATE claim SET Claim_Reason=?, Claim_Amount=?, Claim_Status=?, Claim_Date=?, Application_ID=? WHERE Claim_ID=?";
        
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("ssssss", $claimReason, $claimAmount, $claimStatus, $claimDate, $applicationID, $claimID);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error updating claim: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Claim updated successfully!</p>";
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
    <title>Update Claim</title>
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
    <a href="claim.php" class="back-button">Back to Claim List</a>
</nav>
    <section>
        <h1>Update Claim</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?Claim_ID=' . htmlspecialchars($claimID); ?>" method="post">
            <label for="claim_reason">Claim Reason:</label>
            <input type="text" id="claim_reason" name="claim_reason" value="<?php echo htmlspecialchars($claim['Claim_Reason']); ?>" required>

            <label for="claim_amount">Claim Amount:</label>
            <input type="number" id="claim_amount" name="claim_amount" value="<?php echo htmlspecialchars($claim['Claim_Amount']); ?>" required>

            <label for="claim_status">Claim Status:</label>
            <input type="text" id="claim_status" name="claim_status" value="<?php echo htmlspecialchars($claim['Claim_Status']); ?>" required>

            <label for="claim_date">Claim Date:</label>
            <input type="date" id="claim_date" name="claim_date" value="<?php echo htmlspecialchars($claim['Claim_Date']); ?>" required>

            <label for="application_id">Application ID:</label>
            <select id="application_id" name="application_id" required>
                <?php foreach ($applicationIDs as $appID): ?>
                    <option value="<?php echo htmlspecialchars($appID); ?>" <?php echo ($claim['Application_ID'] == $appID) ? 'selected' : ''; ?>><?php echo htmlspecialchars($appID); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Update Claim">
        </form>
    </section>
</body>
</html>