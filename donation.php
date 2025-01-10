<?php
require_once 'auth_check.php';
check_auth();

// For admin-only pages, also add:
// check_admin();
include "config.php";

// Check if the form is submitted
$donation_added = false; // Flag to check if the donation was added successfully

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $donor_id = mysqli_real_escape_string($conn, $_POST['donor_id']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
    $donation_date = mysqli_real_escape_string($conn, $_POST['donation_date']);
    $payment_method_id = mysqli_real_escape_string($conn, $_POST['payment_method_id']);
    $campaign_id = mysqli_real_escape_string($conn, $_POST['campaign_id']);

    // SQL query to insert donation into the database
    $sql = "INSERT INTO donation (donor_id, total_amount, donation_date, payment_method_id, campaign_id) 
            VALUES ('$donor_id', '$total_amount', '$donation_date', '$payment_method_id', '$campaign_id')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        $donation_added = true; // Set the flag to true
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch donors from the database
$donors = mysqli_query($conn, "SELECT * FROM donor");
$payment_methods = mysqli_query($conn, "SELECT * FROM payment_method");
$campaigns = mysqli_query($conn, "SELECT * FROM campaign");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donations</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // JavaScript function to show the popup if the donation was added
        function showPopup() {
            alert("New donation added successfully.");
        }
    </script>
</head>
<body>
    <header>
        <h1>Donation Management</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="donor.php">Donors</a>
            <a href="campaign.php">Campaigns</a>
            <a href="payment.php">Payment</a>
        </nav>
    </header>

    <section>
        <h2>Add Donation</h2>
        <form action="donation.php" method="POST">
            <label for="donor_id">Donor:</label>
            <select id="donor_id" name="donor_id" required>
                <?php while ($row = mysqli_fetch_assoc($donors)) : ?>
                    <option value="<?= $row['donor_id'] ?>"><?= $row['first_name'] . ' ' . $row['last_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="total_amount">Total Amount:</label>
            <input type="number" step="0.01" id="total_amount" name="total_amount" required>

            <label for="donation_date">Donation Date:</label>
            <input type="date" id="donation_date" name="donation_date" required>

            <label for="payment_method_id">Payment Method:</label>
            <select id="payment_method_id" name="payment_method_id" required>
                <?php while ($row = mysqli_fetch_assoc($payment_methods)) : ?>
                    <option value="<?= $row['payment_method_id'] ?>"><?= $row['method_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="campaign_id">Campaign:</label>
            <select id="campaign_id" name="campaign_id" required>
                <?php while ($row = mysqli_fetch_assoc($campaigns)) : ?>
                    <option value="<?= $row['campaign_id'] ?>"><?= $row['campaign_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Add Donation</button>
        </form>
    </section>

    <?php if ($donation_added): ?>
    <script>
        // Call the function to show the popup when the page reloads after a successful donation
        showPopup();
    </script>
    <?php endif; ?>
</body>
</html>