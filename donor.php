<?php
require_once 'auth_check.php';
check_auth();

// For admin-only pages, also add:
// check_admin();
include "config.php";

// Check if the form is submitted
$donor_added = false; // Flag to check if the donor was added successfully
$error_message = ""; // Variable to store any error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $donor_type_id = mysqli_real_escape_string($conn, $_POST['donor_type_id']);

    // Validate phone number to check if it's exactly 10 digits
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error_message = "Phone number must be exactly 10 digits.";
    } else {
        // SQL query to insert donor into the database
        $sql = "INSERT INTO donor (first_name, last_name, email, phone_number, address, donor_type_id) 
                VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$donor_type_id')";
        
        // Execute the query
        if (mysqli_query($conn, $sql)) {
            $donor_added = true; // Set the flag to true on successful insertion
        } else {
            $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Fetch donor types from the database
$donor_types = mysqli_query($conn, "SELECT * FROM donor_type");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donors</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // JavaScript function to show the popup if the donor was added
        function showPopup() {
            alert("New donor added successfully.");
        }
    </script>
</head>
<body>
    <header>
        <h1>Donation Management</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="donation.php">Donations</a>
            <a href="campaign.php">Campaigns</a>
            <a href="payment.php">Payment</a>
        </nav>
    </header>

    <section>
        <h2>Add Donor</h2>
        <?php if ($error_message): ?>
            <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            
            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea>
            
            <label for="donor_type_id">Donor Type:</label>
            <select id="donor_type_id" name="donor_type_id" required>
                <?php while ($row = mysqli_fetch_assoc($donor_types)) : ?>
                    <option value="<?= $row['donor_type_id'] ?>"><?= htmlspecialchars($row['type_name']) ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Add Donor</button>
        </form>
    </section>

    <?php if ($donor_added): ?>
    <script>
        // Call the function to show the popup when the page reloads after a successful donor addition
        showPopup();
    </script>
    <?php endif; ?>
</body>
</html>
