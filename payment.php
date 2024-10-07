<?php
include "config.php";

// Check if the form is submitted
$payment_method_added = false; // Flag to check if the payment method was added successfully

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $method_name = mysqli_real_escape_string($conn, $_POST['method_name']);
    
    // SQL query to insert the new payment method into the database
    $sql = "INSERT INTO payment_method (method_name) 
            VALUES ('$method_name')";
    
    // Execute the query
    if (mysqli_query($conn, $sql)) {
        $payment_method_added = true; // Set the flag to true on successful insertion
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Fetch payment methods from the database
$payment_methods = mysqli_query($conn, "SELECT * FROM payment_method");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payment Methods</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // JavaScript function to show the popup if the payment method was added
        function showPopup() {
            alert("New payment method added successfully.");
        }
    </script>
</head>
<body>
    <header>
        <h1>Payment Method Management</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="donor.php">Donors</a>
            <a href="donation.php">Donations</a>
            <a href="campaign.php">Campaigns</a>
        </nav>
    </header>

    <section>
        <h2>Add New Payment Method</h2>
        <form method="POST">
            <label for="method_name">Method Name:</label>
            <input type="text" id="method_name" name="method_name" required>
            <button type="submit">Add Payment Method</button>
        </form>
    </section>

    <section>
        <h2>Existing Payment Methods</h2>
        <table>
            <thead>
                <tr>
                    <th>Payment Method Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($payment_methods)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['method_name']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <?php if ($payment_method_added): ?>
    <script>
        // Call the function to show the popup when the page reloads after a successful payment method addition
        showPopup();
    </script>
    <?php endif; ?>
</body>
</html>