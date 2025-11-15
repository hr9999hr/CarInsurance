<?php
include 'connect.php';

// Check if the Bill ID is set in the GET request
if (isset($_GET['Bill_ID'])) {
    $billID = $_GET['Bill_ID'];

    // Fetch the current bill data to confirm deletion
    $sql = "SELECT * FROM bill WHERE Bill_ID = ?";
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $billID);
        $P->execute();
        $result = $P->get_result();

        // Check if the bill exists
        if ($result->num_rows > 0) {
            $bill = $result->fetch_assoc();
        } else {
            echo "<p>No bill found with the given ID.</p>";
            exit;
        }

        $P->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
} else {
    echo "<p>No bill ID provided.</p>";
    exit;
}

// Check if the deletion is confirmed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the DELETE statement
        $sql = "DELETE FROM bill WHERE Bill_ID = ?";
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("s", $billID);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error deleting bill: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Bill deleted successfully!</p>";
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
    <title>Delete Bill</title>
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
    <a href="Bills.php" class="back-button">Back to Bills List</a>
</nav>
    <h2>Delete Bill</h2>
    <p>Are you sure you want to delete the following bill?</p>
    <p><strong>Bill ID:</strong> <?php echo ($bill['Bill_ID']); ?></p>
    <p><strong>Transaction Date:</strong> <?php echo ($bill['Transaction_Date']); ?></p>
    <p><strong>Payment Method:</strong> <?php echo ($bill['Payment_Method']); ?></p>
    <p><strong>Payment Amount:</strong> <?php echo ($bill['Payment_Amount']); ?></p>
    <p><strong>Policy ID:</strong> <?php echo ($bill['Policy_ID']); ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?Bill_ID=' . ($billID); ?>" method="post">
        <input type="submit" class="button" value="Confirm Deletion">
    </form>
    <a href="Bills.php" class="button">Cancel</a>
</body>
</html>