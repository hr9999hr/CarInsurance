<?php
include 'connect.php'; 

// Check if the ID is set in the GET request
if (isset($_GET['id'])) {
    $policyholderID = $_GET['id'];
} else {
    die("No policyholder ID provided.");
}

// Handle the deletion if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Disable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=0");

        // Prepare the DELETE statements
        $sql1 = "DELETE FROM policyholder_agedetail WHERE PolicyHolder_DOB = (SELECT PolicyHolder_DOB FROM policyholder_details WHERE PolicyHolder_NRIC = (SELECT PolicyHolder_NRIC FROM policyholder WHERE PolicyHolder_ID = ?))";
        $sql2 = "DELETE FROM policyholder_details WHERE PolicyHolder_NRIC = (SELECT PolicyHolder_NRIC FROM policyholder WHERE PolicyHolder_ID = ?)";
        $sql3 = "DELETE FROM policyholder WHERE PolicyHolder_ID = ?";

        // Prepare and execute the first statement
        if ($P1 = $conn->prepare($sql1)) {
            $P1->bind_param("s", $policyholderID);
            $P1->execute();
            $P1->close();
        }

        // Prepare and execute the second statement
        if ($P2 = $conn->prepare($sql2)) {
            $P2->bind_param("s", $policyholderID);
            $P2->execute();
            $P2->close();
        }

        // Prepare and execute the third statement
        if ($P3 = $conn->prepare($sql3)) {
            $P3->bind_param("s", $policyholderID);
            $P3->execute();
            $P3->close();
        }

        // Re-enable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=1");

        // Commit the transaction
        $conn->commit();

        header("Location: PolicyHolderlist.php");

        exit();
    } catch (Exception $e) {
        // Rollback the transaction if something failed
        $conn->rollback();
        echo "<p>Error deleting policyholder: " . $e->getMessage() . "</p>";
    }
} else {
    // Display the confirmation form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Delete Policy Holder</title>
        <link rel="stylesheet" href="menu_style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .delete-form {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                margin: auto;
            }
            h1 {
                text-align: center;
                color: rgb(37, 119, 185);
            }
            .button {
                padding: 10px 15px;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                font-size: 16px;
                margin-top: 10px;
            }
            .confirm {
                background-color: #f44336; /* Red */
                color: white;
            }
            .cancel {
                background-color: #4CAF50; /* Green */
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="delete-form">
            <h1>Delete Policy Holder</h1>
            <p>Are you sure you want to delete the policyholder with ID: <strong><?php echo htmlspecialchars($policyholderID); ?></strong>?</p>
            <form action="" method="POST">
                <input type="hidden" name ="id" value="<?php echo htmlspecialchars($policyholderID); ?>">
                <button type="submit" class="button confirm">Delete</button>
                <a href="PolicyHolderlist.php" class="button cancel">Cancel</a>
            </form>
        </div>
    </body>
    </html>
    <?php
}

mysqli_close($conn);
?>