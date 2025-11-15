<?php
include 'connect.php';

// Check if the Claim ID is set in the GET request
if (isset($_GET['Claim_ID'])) {
    $claimID = $_GET['Claim_ID'];

    // Fetch the current claim data to confirm deletion
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

// Check if the deletion is confirmed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the DELETE statement
        $sql = "DELETE FROM claim WHERE Claim_ID = ?";
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("s", $claimID);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error deleting claim: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Claim deleted successfully!</p>";
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
    <title>Delete Claim</title>
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
    <a href="claim.php" class="back-button">Back to Claim List</a>
</nav>
    <h2>Delete Claim</h2>
    <p>Are you sure you want to delete the following claim?</p>
    <p><strong>Claim ID:</strong> <?php echo ($claim['Claim_ID']); ?></p>
    <p><strong>Reason:</strong> <?php echo ($claim['Claim_Reason']); ?></p>
    <p><strong>Amount:</strong> <?php echo ($claim['Claim_Amount']); ?></p>
    <p><strong>Status:</strong> <?php echo ($claim['Claim_Status']); ?></p>
    <p><strong>Claim Date:</strong> <?php echo ($claim['Claim_Date']); ?></p>
    <p><strong>Application ID:</strong> <?php echo ($claim['Application_ID']); ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?Claim_ID=' . ($claimID); ?>" method="post">
        <input type="submit" class="button" value="Confirm Deletion">
    </form>
    <a href="claim.php" class="button">Cancel</a>
</body>
</html>