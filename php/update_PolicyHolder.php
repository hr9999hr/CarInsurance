<?php
include 'connect.php';

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing details of the policyholder
    $P = $conn->prepare("
    SELECT p.*, d.PolicyHolder_FirstName, d.PolicyHolder_LastName, d.PolicyHolder_Gender, d.PolicyHolder_NRIC, d.PolicyHolder_Nationality, a.PolicyHolder_Age, d.PolicyHolder_DOB 
    FROM policyholder p 
    JOIN policyholder_details d ON p.PolicyHolder_NRIC = d.PolicyHolder_NRIC 
    JOIN policyholder_agedetail a ON d.PolicyHolder_DOB = a.PolicyHolder_DOB
    WHERE p.PolicyHolder_ID = ?
    ");
    if (!$P) {
        die("Prepare failed: " . $conn->error);
    }

    $P->bind_param("s", $id);
    $P->execute();
    $result = $P->get_result();

    if ($result->num_rows > 0) {
        $policyholder = $result->fetch_assoc();
    } else {
        echo "No policyholder found.";
        exit;
    }
    
    $P->free_result(); // Free the result set
    $P->close(); // Close the statement
} else {
    echo "No ID provided.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data 
    $policyholderID = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $nric = $_POST['nric'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];
    $marital_status = $_POST['marital_status'];
    $occupation = $_POST['occupation'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $license_num = $_POST['license_num'];
    $amount_due = $_POST['amount_due'];
    $age = date_diff(date_create($dob), date_create('today'))->y; // Calculate age

    // Step 1: Check if the new DOB exists in the policyholder_agedetail table
    $P_check_dob = $conn->prepare("SELECT COUNT(*) FROM policyholder_agedetail WHERE PolicyHolder_DOB = ?");
    $P_check_dob->bind_param("s", $dob); // assuming $dob is the new date of birth
    $P_check_dob->execute();
    $P_check_dob->bind_result($dob_exists);
    $P_check_dob->fetch();
    $P_check_dob->close(); // Close the statement

    if ($dob_exists == 0) {
        // Step 2: Insert the new DOB into policyholder_agedetail
        $P3 = $conn->prepare("INSERT INTO policyholder_agedetail (PolicyHolder_DOB, PolicyHolder_Age) VALUES (?, ?)");
        $P3->bind_param("si", $dob, $age); // Insert the new DOB and age
        if (!$P3->execute()) {
            echo "Error inserting into policyholder_agedetail: " . $P3->error;
            exit;
        }
        $P3->close(); // Close the statement
    }

    // Prepare and bind for policyholder update
    $P1 = $conn->prepare("UPDATE policyholder SET PolicyHolder_NRIC = ?, PolicyHolder_MaritalStatus = ?, PolicyHolder_Occupation = ?, PolicyHolder_PhoneNum = ?, PolicyHolder_Email = ?, PolicyHolder_Address = ?, PolicyHolder_LicenseNum = ?, PolicyHolder_AmountDue = ? WHERE PolicyHolder_ID = ?");
    if (!$P1) {
        die("Error preparing policyholder update: " . $conn->error);
    }
    // Bind parameters correctly
    $P1->bind_param("sssssssss", $nric, $marital_status, $occupation, $phone, $email, $address, $license_num, $amount_due, $policyholderID);

    // Execute the statement for policyholder update
    if ($P1->execute()) {
        if ($P1->affected_rows === 0) {
            echo "No rows affected in policyholder update.";
        }
        $P1->close(); // Close the statement

        // Prepare and bind for PolicyHolder_Details update
        $P2 = $conn->prepare("UPDATE policyholder_details SET PolicyHolder_FirstName = ?, PolicyHolder_LastName = ?, PolicyHolder_DOB = ?, PolicyHolder_Gender = ?, PolicyHolder_Nationality = ? WHERE PolicyHolder_NRIC = ?");
        if (!$P2) {
            die("Error preparing details update: " . $conn->error);
        }
        $P2->bind_param("ssssss", $first_name, $last_name, $dob, $gender, $nationality, $nric);

        // Execute the statement for PolicyHolder_Details update
        if ($P2->execute()) {
            if ($P2->affected_rows === 0) {
                echo "No rows affected in policyholder details update.";
            }
            $P2->close(); // Close the statement

            //Update the age in policyholder_agedetail
            $P4 = $conn->prepare("UPDATE policyholder_agedetail SET PolicyHolder_Age = ? WHERE PolicyHolder_DOB = ?");
            if (!$P4) {
                die("Error preparing age update: " . $conn->error);
            }
            $P4->bind_param("is", $age, $dob); // Update the age in policyholder_agedetail

            if ($P4->execute()) {
                if ($P4->affected_rows === 0) {
                    echo "No rows affected in policyholder age update.";
                }
                $P4->close(); // Close the statement

                //Delete the old DOB from policyholder_agedetail only if DOB has changed
                if ($policyholder['PolicyHolder_DOB'] !== $dob) {
                    $P_delete_old_dob = $conn->prepare("DELETE FROM policyholder_agedetail WHERE PolicyHolder_DOB = ?");
                    $P_delete_old_dob->bind_param("s", $policyholder['PolicyHolder_DOB']);
                    if (!$P_delete_old_dob->execute()) {
                        echo "Error deleting old DOB from policyholder_agedetail: " . $P_delete_old_dob->error;
                        exit;
                    }
                    $P_delete_old_dob->close(); // Close the statement
                }

                $P = $conn->prepare("
                SELECT p.*, d.PolicyHolder_FirstName, d.PolicyHolder_LastName, d.PolicyHolder_Gender, d.PolicyHolder_NRIC, d.PolicyHolder_Nationality, a.PolicyHolder_Age, d.PolicyHolder_DOB
                FROM policyholder p 
                JOIN policyholder_details d ON p.PolicyHolder_NRIC = d.PolicyHolder_NRIC 
                JOIN policyholder_agedetail a ON d.PolicyHolder_DOB = a.PolicyHolder_DOB
                WHERE p.PolicyHolder_ID = ?
                ");
                $P->bind_param("s", $policyholderID);
                $P->execute();
                $result = $P->get_result();
                $policyholder = $result->fetch_assoc();

                $P->free_result(); // Free the result set
                $P->close(); // Close the statement

                echo "<div style='text-align: center; padding: 10px; background-color:rgb(46, 171, 229); color:rgb(98, 201, 224); font-weight: bold; border: 1px solidrgb(195, 213, 230);'>
                        &#10004; Policyholder details updated successfully.
                    </div>";
            } else {
                echo "Error updating age: " . $P4->error;
            }
        } else {
            echo "Error updating details: " . $P2->error;
        }
    } else {
        echo "Error updating policyholder: " . $P1->error;
    }
}

$formatted_dob = isset($policyholder['PolicyHolder_DOB']) ? $policyholder['PolicyHolder_DOB'] : '';


// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Policy Holder</title>
    <link rel="stylesheet" href="styleform.css">
</head>
<body>

<header>
    <h1>Welcome to Safedrive Insurance</h1>
</header>

<nav>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="about.php">About</a>
        <a href="services.php">Services</a>
        <a href="contact.php">Contact</a>
    </div>
    <a href="policyholderlist.php" class="back-button">Back to Policyholder List</a>
</nav>

<h2>Edit Policyholder Data</h2>
<form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id; ?>" method="post">

    <label for="id">Policy Holder ID:</label>
    <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_ID']); ?>" readonly required>
    
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars ($policyholder['PolicyHolder_FirstName']); ?>" required>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars ($policyholder['PolicyHolder_LastName']); ?>" required>

    <label for="gender">Gender:</label>
    <input type="radio" id="gender_male" name="gender" value="Male" checked> Male
    <input type="radio" id="gender_female" name="gender" value="Female"> Female

    <label for="nric">NRIC:</label>
    <input type="text" id="nric" name="nric" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_NRIC']); ?>" readonly required>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_DOB']); ?>" required>

    <label for="age">Age:</label>
    <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_Age']); ?>" required>

    <label for="nationality">Nationality:</label>
    <input type="radio" id="nationality_malaysian" name="nationality" value="Malaysian"<?php echo ($policyholder['PolicyHolder_Nationality'] === 'Malaysian') ? 'checked' : ''; ?>> Malaysian
    <input type="radio" id="nationality_non_malaysian" name="nationality" value="Non-malaysian"<?php echo ($policyholder['PolicyHolder_Nationality'] === 'Non-malaysian') ? 'checked' : ''; ?>> Non-malaysian
    
    <label for="marital_status">Marital Status:</label>
    <input type="radio" id="marital_status_single" name="marital_status" value="Single"<?php echo ($policyholder['PolicyHolder_MaritalStatus'] === 'Single') ? 'checked' : ''; ?>> Single
    <input type="radio" id="marital_status_married" name="marital_status" value="Married"<?php echo ($policyholder['PolicyHolder_MaritalStatus'] === 'Married') ? 'checked' : ''; ?>> Married
    <input type="radio" id="marital_status_divorced" name="marital_status" value="Divorced" <?php echo ($policyholder['PolicyHolder_MaritalStatus'] === 'Divorced') ? 'checked' : ''; ?>> Divorced

    <label for="occupation">Occupation:</label>
    <input type="text" id="occupation" name="occupation" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_Occupation']); ?>" required>

    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_PhoneNum']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_Email']); ?>" required>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_Address']); ?>" required>

    <label for="license_num">License Number:</label>
    <input type="text" id="license_num" name="license_num" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_LicenseNum']); ?>" required>

    <label for="amount_due">Amount Due:</label>
    <input type="text" id="amount_due" name="amount_due" value="<?php echo htmlspecialchars($policyholder['PolicyHolder_AmountDue']); ?>" required>

    <input type="submit" value="Update Policyholder">
</form>

</body>
</html>