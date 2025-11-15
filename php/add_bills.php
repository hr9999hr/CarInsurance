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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $billID = $_POST['bill_id'];
    $transactionDate = $_POST['transaction_date'];
    $paymentMethod = $_POST['payment_method'];
    $paymentAmount = $_POST['payment_amount'];
    $policyID = $_POST['policy_id'];

    // Prepare the INSERT statement for the bill
    $sql = "INSERT INTO bill (Bill_ID, Transaction_Date, Payment_Method, Payment_Amount, Policy_ID) VALUES (?, ?, ?, ?, ?)";
    
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("sssss", $billID, $transactionDate, $paymentMethod, $paymentAmount, $policyID);
        
        // Execute the statement
        if ($P->execute()) {
            echo "<p>New bill added successfully!</p>";
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
    <title>Add Bill</title>
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
        <h1>Add New Bill</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="bill_id">Bill ID:</label>
            <input type="text" id="bill_id" name="bill_id" required>

            <label for="transaction_date">Transaction Date:</label>
            <input type="date" id="transaction_date" name="transaction_date" required>

            <label for="payment_method">Payment Method:</label>
            <input type="text" id="payment_method" name="payment_method" required>

            <label for="payment_amount">Payment Amount:</label>
            <input type="number" id="payment_amount" name="payment_amount" required>

            <label for="policy_id">Policy ID:</label>
            <select id="policy_id" name="policy_id" required>
                <?php foreach ($policyIDs as $policyID): ?>
                    <option value="<?php echo ($policyID); ?>"><?php echo ($policyID); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Add Bill">
        </form>
    </section>
</body>
</html>