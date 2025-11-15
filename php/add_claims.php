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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $claimID = $_POST['claim_id'];
    $claimReason = $_POST['claim_reason'];
    $claimAmount = $_POST['claim_amount'];
    $claimStatus = $_POST['claim_status'];
    $claimDate = $_POST['claim_date'];
    $applicationID = $_POST['application_id'];

    // Prepare the INSERT statement for the claim
    $sql = "INSERT INTO claim (Claim_ID, Claim_Reason, Claim_Amount, Claim_Status, Claim_Date, Application_ID) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("ssssss", $claimID, $claimReason, $claimAmount, $claimStatus, $claimDate, $applicationID);
        
        // Execute the statement
        if ($P->execute()) {
            echo "<p>New claim added successfully!</p>";
        } else {
            echo "<p>Error: " . $P->error . "</p>";
        }
        
        // Close the statement
        $P->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Claim</title>
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
        <h1>Add New Claim</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="claim_id">Claim ID:</label>
            <input type="text" id="claim_id" name="claim_id" required>

            <label for="claim_reason">Claim Reason:</label>
            <input type="text" id="claim_reason" name="claim_reason" required>

            <label for="claim_amount">Claim Amount:</label>
            <input type="number" id="claim_amount" name="claim_amount" required>

            <label for="claim_status">Claim Status:</label>
            <input type="text" id="claim_status" name="claim_status" required>

            <label for="claim_date">Claim Date:</label>
            <input type="date" id="claim_date" name="claim_date" required>

            <label for="application_id">Application ID:</label>
            <select id="application_id" name="application_id" required>
                <?php foreach ($applicationIDs as $appID): ?>
                    <option value="<?php echo ($appID); ?>"><?php echo($appID); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Add Claim">
        </form>
    </section>
</body>
</html>