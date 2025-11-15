<?php
include 'connect.php';

// Check if the Application ID is set in the GET request
if (isset($_GET['Application_ID'])) {
    $applicationID = $_GET['Application_ID'];

    // Fetch the current application data to confirm deletion
    $sql = "SELECT * FROM application WHERE Application_ID = ?";
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $applicationID);
        $P->execute();
        $result = $P->get_result();

        // Check if the application exists
        if ($result->num_rows > 0) {
            $application = $result->fetch_assoc();
        } else {
            echo "<p>No application found with the given ID.</p>";
            exit;
        }

        $P->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
} else {
    echo "<p>No application ID provided.</p>";
    exit;
}

// Check if the deletion is confirmed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the DELETE statement
        $sql = "DELETE FROM application WHERE Application_ID = ?";
        if ($P = $conn->prepare($sql)) {
            $P->bind_param("s", $applicationID);
            
            // Execute the statement
            if (!$P->execute()) {
                throw new Exception("Error deleting application: " . $P->error);
            }
            
            // Close the statement
            $P->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        echo "<p>Application deleted successfully!</p>";
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
    <title>Delete Application</title>
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
    <a href="application.php" class="back-button">Back to Application List</a>
</nav>
    <h2>Delete Application</h2>
    <p>Are you sure you want to delete the following application?</p>
    <p><strong>Application ID:</strong> <?php echo ($application['Application_ID']); ?></p>
    <p><strong>Application Date:</strong> <?php echo ($application['Application_Date']); ?></p>
    <p><strong>Application Status:</strong> <?php echo ($application['Application_Status']); ?></p>
    <p><strong>Policy ID:</strong> <?php echo ($application['Policy_ID']); ?></p>
    <p><strong>Vehicle Registration Number:</strong> <?php echo ($application['Vehicle_RegNum']); ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?Application_ID=' . ($applicationID); ?>" method="post">
        <input type="submit" class="button" value="Confirm Deletion">
    </form>
    <a href="application.php" class="button">Cancel</a>
</body>
</html>