<?php
include 'connect.php';

// Check if the ID is set in the GET request
if (isset($_GET['id'])) {
    $policyID = $_GET['id'];

    // Prepare the DELETE statement
    $sql = "DELETE FROM policy WHERE Policy_ID = ?";
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $policyID);
        
        // Execute the statement
        if ($P->execute()) {
            $message = "Policy deleted successfully!";
        } else {
            $message = "Error: " . $P->error;
        }
        
        // Close the statement
        $P->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }
} else {
    $message = "No policy ID provided.";
}

mysqli_close($conn);
?>

<!-- HTML code for the Delete Confirmation -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Policy</title>
    <link rel="stylesheet" href="menu_style.css">
</head>
<body>
    <section>
        <h1>Delete Policy</h1>
        <p><?php echo ($message); ?></p>
        <a href="Policylist.php" class="button">Back to Policy List</a>
    </section>
</body>
</html>