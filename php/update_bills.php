<?php
include 'connect.php';

// Fetch policy IDs for the dropdown
$policyIDs = [];
$sql = "SELECT Policy_ID FROM policy";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $policyIDs[] = $row['Policy_ID'];
    }
    $result->free();
} else {
    echo "<p>Error fetching policy IDs: " . $conn->error . "</p>";
    exit;
}

// Check if the Bill ID is set in the GET request
if (isset($_GET['Bill_ID'])) {
    $billID = $_GET['Bill_ID'];

    // Fetch the current bill data
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $transactionDate = $_POST['transaction_date'];
    $paymentMethod = $_POST['payment_method'];
    $paymentAmount = $_POST['payment_amount'];
    $policyID = $_POST['policy_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the UPDATE statement
        $sql = "UPDATE bill SET Transaction_Date=?, Payment_Method=?, Payment_Amount=?, Policy_ID=? WHERE Bill_ID=?";
        
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("sssss", $transactionDate, $paymentMethod, $paymentAmount, $policyID, $billID);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error updating bill: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Bill updated successfully!</p>";
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
    <title>Update Bill</title>
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
    <a href="Bills.php" class="back-button">Back to Bills List</a>
</nav>
    <section>
        <h1>Update Bill</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?Bill_ID=' . htmlspecialchars($billID); ?>" method="post">
            <label for="transaction_date">Transaction Date:</label>
            <input type="date" id="transaction_date" name="transaction_date" value="<?php echo htmlspecialchars($bill['Transaction_Date']); ?>" required>

            <label for="payment_method">Payment Method:</label>
            <input type="text" id="payment_method" name="payment_method" value="<?php echo htmlspecialchars($bill['Payment_Method']); ?>" required>

            <label for="payment_amount">Payment Amount:</label>
            <input type="number" id="payment_amount" name="payment_amount" value="<?php echo htmlspecialchars($bill['Payment_Amount']); ?>" required>

            <label for="policy_id">Policy ID:</label>
            <select id="policy_id" name="policy_id" required>
                <?php foreach ($policyIDs as $policyID): ?>
                    <option value="<?php echo htmlspecialchars($policyID); ?>" <?php echo ($bill['Policy_ID'] == $policyID) ? 'selected' : ''; ?>><?php echo htmlspecialchars($policyID); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Update Bill">
        </form>
    </section>
</body>
</html>