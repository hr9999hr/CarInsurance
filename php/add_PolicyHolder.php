<?php
include 'connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $nric = $_POST['nric'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $marital_status = $_POST['marital_status'];
    $occupation = $_POST['occupation'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $license_num = $_POST['license_num'];
    $amount_due = $_POST['amount_due'];
    $age = date_diff(date_create($dob), date_create('today'))->y; // Calculate age

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare and bind for PolicyHolder_AgeDetail
        $P1 = $conn->prepare("INSERT INTO policyholder_agedetail (PolicyHolder_DOB, PolicyHolder_Age) VALUES (?, ?)");
        $P1->bind_param("si", $dob, $age);

        // Execute the statement for PolicyHolder_AgeDetail
        if (!$P1->execute()) {
            throw new Exception("Error inserting into policyholder_agedetail: " . $P1->error);
        }
        $P1->close(); // Close the statement for PolicyHolder_AgeDetail

        // Prepare and bind for PolicyHolder_Details
        $P2 = $conn->prepare("INSERT INTO policyholder_details (PolicyHolder_NRIC, PolicyHolder_FirstName, PolicyHolder_LastName, PolicyHolder_DOB, PolicyHolder_Gender, PolicyHolder_Nationality) VALUES (?, ?, ?, ?, ?, ?)");
        $P2->bind_param("ssssss", $nric, $first_name, $last_name, $dob, $gender, $nationality);

        // Execute the statement for PolicyHolder_Details
        if (!$P2->execute()) {
            throw new Exception("Error inserting into policyholder_details: " . $P2->error);
        }
        $P2->close(); // Close the statement for PolicyHolder_Details

        // Prepare and bind for policyholder
        $P3 = $conn->prepare("INSERT INTO policyholder (PolicyHolder_MaritalStatus, PolicyHolder_Occupation, PolicyHolder_PhoneNum, PolicyHolder_Email, PolicyHolder_Address, PolicyHolder_LicenseNum, PolicyHolder_AmountDue, PolicyHolder_NRIC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $P3->bind_param("ssssssss", $marital_status, $occupation, $phone, $email, $address, $license_num, $amount_due, $nric);

        // Execute the statement for policyholder
        if (!$P3->execute()) {
            throw new Exception("Error inserting into policyholder: " . $P3->error);
        }
        $P3->close(); // Close the statement for policyholder

        // Commit transaction
        $conn->commit();

        echo "New policyholder added successfully.";
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
    <title>Add Policyholder Data</title>
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
    <a href="policyholderlist.php" class="back-button">Back to Policyholder List</a>
</nav>

<h2>Add Policyholder Data</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" required>

    <label for="nric">NRIC:</label>
    <input type="text" id="nric" name="nric" required>

    <label for="gender">Gender:</label>
    <input type="radio" id="gender" name="gender" value="Male">Male
    <input type="radio" id="gender" name="gender" value="Female">Female
   
    <label for="nationality">Nationality:</label>
    <input type="radio" id="nationality" name="nationality" value="Malaysian">Malaysian
    <input type="radio" id="nationality" name="nationality" value="Non-malaysian">Non-malaysian
     
    <label for="marital_status">Marital Status:</label>
    <input type="radio" id="marital_status" name="marital_status" value="Single">Single
    <input type="radio" id="marital_status" name="marital_status" value="Married">Married
    <input type="radio" id="marital_status" name="marital_status" value="Divorced">Divorced
    
    <label for="occupation">Occupation:</label>
    <input type="text" id="occupation" name="occupation" required>

    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required>

    <label for="license_num">License Number:</label>
    <input type="text" id="license_num" name="license_num" required>

    <label for="amount_due">Amount Due:</label>
    <input type="text" id="amount_due" name="amount_due" required>

    <input type="submit" value="Add Policyholder">
</form>

</body>
</html>