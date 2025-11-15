<?php
include 'connect.php';

// Check if the ID is set in the GET request
if (isset($_GET['id'])) {
    $policyID = $_GET['id'];

    // Fetch the current policy data
    $sql = "SELECT p.*, i.Policy_PremiumAmount, i.Policy_NCD, i.Policy_Betterment, i.Extension_ID, i.Policy_LiabilityCoverageLimit, i.Coverage_ID
            FROM policy p 
            JOIN policy_info i ON p.Info_ID = i.Info_ID 
            WHERE p.Policy_ID = ?";
    
    if ($P = $conn->prepare($sql)) {
        $P->bind_param("s", $policyID);
        $P->execute();
        $result = $P->get_result();

        // Check if the policy exists
        if ($result->num_rows > 0) {
            $policy = $result->fetch_assoc();
        } else {
            echo "<p>No policy found with the given ID.</p>";
            exit;
        }

        $P->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $policyID = $_POST['id'];
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
        // Prepare and bind for policy_info update
        $P1 = $conn->prepare("UPDATE policy_info SET Policy_PremiumAmount = ?, Policy_NCD = ?, Policy_Betterment = ?, Extension_ID = ?, Policy_LiabilityCoverageLimit = ?, Coverage_ID = ? WHERE Info_ID = ?");
        $P1->bind_param("sssssss", $premium_amount, $ncd, $betterment, $extension_id, $liabilitycoveragelimit, $coverage_id, $info_id);

        // Execute the statement for policy_info
        if (!$P1->execute()) {
            throw new Exception("Error updating policy_info: " . $P1->error);
        }
        $P1->close(); // Close the statement for policy_info

        // Prepare and bind for policy update
        $P2 = $conn->prepare("UPDATE policy SET Info_ID = ?, Policy_Start = ?, Policy_END = ?, Policy_Type = ?, Policy_Purpose = ? WHERE Policy_ID = ?");
        $P2->bind_param("ssssss", $info_id, $start, $end, $type, $purpose, $policyID);

        // Execute the statement for policy
        if (!$P2->execute()) {
            throw new Exception("Error updating policy: " . $P2->error);
        }
        $P2->close(); // Close the statement for policy

        // Commit transaction
        $conn->commit();

            $P = $conn->prepare($sql);
            $P->bind_param("s", $policyID);
            $P->execute();
            $result = $P->get_result();
            $policy = $result->fetch_assoc();

            echo "<div style='text-align: center; padding: 10px; background-color:rgb(46, 171, 229); color:rgb(98, 201, 224); font-weight: bold; border: 1px solidrgb(195, 213, 230);'>
                        &#10004; Policy details updated successfully.
                    </div>";

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
    <title>Update Policy Data</title>
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

<h2>Update Policy Data</h2>
<form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $policyID; ?>" method="post"> 

    <label for="id">Policy ID:</label>
    <input type="text" id="id" name="id" value="<?php echo ($policy['Policy_ID']); ?>" readonly required>
    
    <label for="info_id">Info ID:</label>
    <input type="text" id="info_id" name="info_id" value="<?php echo ($policy['Info_ID']); ?>" readonly required>

    <label for="start">Policy Start:</label>
    <input type="date" id="start" name="start" value="<?php echo ($policy['Policy_Start']); ?>" required>

    <label for="end">Policy End:</label>
    <input type="date" id="end" name="end" value="<?php echo ($policy['Policy_END']); ?>" required>

    <label for="type">Type:</label>
    <input type="radio" id="type" name="type" value="Comprehensive" <?php echo ($policy['Policy_Type'] == 'Comprehensive') ? 'checked' : ''; ?> required>Comprehensive
    <input type="radio" id="type" name="type" value="Third Party" <?php echo ($policy['Policy_Type'] == 'Third Party') ? 'checked' : ''; ?> required>Third Party
   
    <label for="purpose">Purpose:</label>
    <input type="radio" id="purpose" name="purpose" value="Commercial" <?php echo ($policy['Policy_Purpose'] == 'Commercial') ? 'checked' : ''; ?> required>Commercial
    <input type="radio" id="purpose" name="purpose" value="Personal" <?php echo ($policy['Policy_Purpose'] == 'Personal') ? 'checked' : ''; ?> required>Personal
    
    <label for="premium_amount">Premium Amount RM:</label>
    <input type="text" id="premium_amount" name="premium_amount" value="<?php echo ($policy['Policy_PremiumAmount']); ?>" required>

    <label for="ncd">NCD:</label>
    <input type="text" id="ncd" name="ncd" value="<?php echo ($policy['Policy_NCD']); ?>" required>

    <label for="Betterment">Betterment:</label>
    <input type="text" id="Betterment" name="Betterment" value="<?php echo ($policy['Policy_Betterment']); ?>" required>

    <label for="extension_id">Extension ID:</label>
    <input type="radio" id="extension_id" name="extension_id" value="1" <?php echo ($policy['Extension_ID'] == '1') ? 'checked' : ''; ?> required>1: Convulsion of nature
    <input type="radio" id="extension_id" name="extension_id" value="2" <?php echo ($policy['Extension_ID'] == '2') ? 'checked' : ''; ?> required>2: Breakage of glass
    <input type="radio" id="extension_id" name="extension_id" value="3" <?php echo ($policy['Extension_ID'] == '3') ? 'checked' : ''; ?> required>3: Vehicle accessories

    <label for="LiabilityCoverageLimit">Liability Coverage Limit:</label>
    <input type="text" id="LiabilityCoverageLimit" name="LiabilityCoverageLimit" value="<?php echo ($policy['Policy_LiabilityCoverageLimit']); ?>" required>

    <label for="Coverage_ID">Coverage ID:</label>
    <input type="text" id="Coverage_ID" name="Coverage_ID" value="<?php echo ($policy['Coverage_ID']); ?>" required>

    <input type="submit" value="Update Policy">
</form>

</body>
</html>