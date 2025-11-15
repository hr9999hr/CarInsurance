<?php
include 'connect.php';

// Check if the ID is set in the GET request
if (isset($_GET['id'])) {
    $policyID = $_GET['id'];

    // Prepare the SELECT statement to fetch policy details
    $sql = "SELECT p.*, i.Info_ID, i.Policy_PremiumAmount, i.Policy_NCD, i.Policy_Betterment, i.Extension_ID, i.Policy_LiabilityCoverageLimit, e.Extension_Type
            FROM policy p 
            JOIN policy_info i ON p.Info_ID = i.Info_ID 
            JOIN policy_extension e ON i.Extension_ID = e.Extension_ID
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

    // Prepare the SELECT statement to fetch coverage details
    $sql_coverage = "SELECT c.Coverage_ID, c.Coverage_Detail
                     FROM policy_coveragedetail c
                     JOIN policy_info i ON c.Coverage_ID = i.Coverage_ID
                     WHERE i.Info_ID = ?";

    if ($P_coverage = $conn->prepare($sql_coverage)) {
        $P_coverage->bind_param("s", $policy['Info_ID']);
        $P_coverage->execute();
        $result_coverage = $P_coverage->get_result();

        $coverages = [];
        while ($row = $result_coverage->fetch_assoc()) {
            $coverages[] = $row;
        }

        $P_coverage->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
        exit;
    }
} else {
    echo "<p>No policy ID provided.</p>";
    exit;
}

mysqli_close($conn);
?>

<!-- HTML code for displaying the policy details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Policy</title>
    <link rel="stylesheet" href="menu_style.css">
</head>
<body>
    <section>
        <h1>Policy Details</h1>
        <table>
            <tr>
                <th>Policy ID</th>
                <td><?php echo htmlspecialchars($policy['Policy_ID']); ?></td>
            </tr>
            <tr>
                <th>Info ID</th>
                <td><?php echo htmlspecialchars($policy['Info_ID']); ?></td>
            </tr>
            <tr>
                <th>Policy Start</th>
                <td><?php echo htmlspecialchars($policy['Policy_Start']); ?></td>
            </tr>
            <tr>
                <th>Policy End</th>
                <td><?php echo htmlspecialchars($policy['Policy_END']); ?></td>
            </tr>
            <tr>
                <th>Policy Type</th>
                <td><?php echo htmlspecialchars($policy['Policy_Type']); ?></td>
            </tr>
            <tr>
                <th>Policy Purpose</th>
                <td><?php echo htmlspecialchars($policy['Policy_Purpose']); ?></td>
            </tr>
            <tr>
                <th>Premium Amount</th>
                <td><?php echo htmlspecialchars($policy['Policy_PremiumAmount']); ?></td>
            </tr>
            <tr>
                <th>NCD</th>
                <td><?php echo htmlspecialchars($policy['Policy_NCD']); ?></td>
            </tr>
            <tr>
                <th>Betterment</th>
                <td><?php echo htmlspecialchars($policy['Policy_Betterment']); ?></td>
            </tr>
            <tr>
                <th>Extension ID</th>
                <td><?php echo htmlspecialchars($policy['Extension_ID']); ?></td>
            </tr>
            <tr>
                <th>Extension Type</th>
                <td><?php echo htmlspecialchars($policy['Extension_Type']); ?></td>
            </tr>
            <tr>
                <th>Liability Coverage Limit</th>
                <td><?php echo htmlspecialchars($policy['Policy_LiabilityCoverageLimit']); ?></td>
            </tr>
            <tr>
                <th>Coverage Details</th>
                <td>
                    <ul>
                        <?php foreach ($coverages as $coverage): ?>
                            <li><?php echo htmlspecialchars($coverage['Coverage_ID']) . ': ' . htmlspecialchars($coverage['Coverage_Detail']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
        </table>
        <a href="Policylist.php" class="button">Back to Policy List</a>
    </section>
</body>
</html>