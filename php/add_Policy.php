<?php
include 'connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $info_id = $_POST['info_id'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $type = $_POST['type'];
    $purpose = $_POST['purpose'];
    $premium_amount = $_POST['premium_amount'];
    $ncd = $_POST['ncd'];
    $betterment = $_POST['Betterment'];
    $extension_id = $_POST['extension_id'];
    $liabilitycoveragelimit = $_POST['LiabilityCoverageLimit'];
    $coverage_id = $_POST['Coverage_ID'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare and bind for policy_info
        $P1 = $conn->prepare("INSERT INTO policy_info (Info_ID, Policy_PremiumAmount, Policy_NCD, Policy_Betterment, Extension_ID, Policy_LiabilityCoverageLimit, Coverage_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $P1->bind_param("issssss", $info_id, $premium_amount, $ncd, $betterment, $extension_id, $liabilitycoveragelimit, $coverage_id);

        // Execute the statement for policy_info
        if (!$P1->execute()) {
            throw new Exception("Error inserting into policy_info: " . $P1->error);
        }
        $P1->close(); // Close the statement for policy_info

        // Prepare and bind for policy
        $P2 = $conn->prepare("INSERT INTO policy (Info_ID, Policy_Start, Policy_END, Policy_Type, Policy_Purpose) VALUES (?, ?, ?, ?, ?)");
        $P2->bind_param("issss", $info_id, $start, $end, $type, $purpose);

        // Execute the statement for policy
        if (!$P2->execute()) {
            throw new Exception("Error inserting into policy: " . $P2->error);
        }
        $P2->close(); // Close the statement for policy

        // Commit transaction
        $conn->commit();

        echo "New policy added successfully.";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

        echo $e->getMessage();
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Policy Data</title>
    <link rel="stylesheet" href="styleform.css">
</head>
<body>

<header>
    <h1>Welcome to Safedrive Insurance</h1>
</header>

<nav>
    <div class="nav-links">
        <a href="home.php">Home</a>
    </div>
    <a href="Policylist.php" class="back-button">Back to Policy List</a>
</nav>

<h2>Add Policy Data</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 

    <label for="info_id">Info ID:</label>
    <input type="text" id="info_id" name="info_id" required>

    <label for="start">Policy Start:</label>
    <input type="date" id="start" name="start" required>

    <label for="end">Policy End:</label>
    <input type="date" id="end" name="end" required>

    <label for="type">Type:</label>
    <input type="radio" id="type" name="type" value="Comprehensive" required>Comprehensive
    <input type="radio" id="type" name="type" value="Third Party" required>Third Party
   
    <label for="purpose">Purpose:</label>
    <input type="radio" id="purpose" name="purpose" value="Commercial" required>Commercial
    <input type="radio" id="purpose" name="purpose" value="Personal" required>Personal
    
    <label for="premium_amount">Premium Amount RM:</label>
    <input type="text" id="premium_amount" name="premium_amount" required>

    <label for="ncd">NCD:</label>
    <input type="text" id="ncd" name="ncd" required>

    <label for="Betterment">Betterment:</label>
    <input type="text" id="Betterment" name="Betterment" required>

    <label for="extension_id">Extension ID:</label>
    <input type="radio" id="extension_id" name="extension_id" value="1" required>1: Convulsion of nature
    <input type="radio" id="extension_id" name="extension_id" value="2" required>2: Breakage of glass
    <input type="radio" id="extension_id" name="extension_id" value="3" required>3: vehicle accessories

    <label for="LiabilityCoverageLimit">Liability Coverage Limit:</label>
    <input type="text" id="LiabilityCoverageLimit" name="LiabilityCoverageLimit" required>

    <label for="Coverage_ID">Coverage ID:</label>
    <input type="text" id="Coverage_ID" name="Coverage_ID" required>

    <input type="submit" value="Add Policy">
</form>

</body>
</html>