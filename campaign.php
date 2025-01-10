<?php
require_once 'auth_check.php';
check_auth();

// For admin-only pages, also add:
// check_admin();
include "config.php";

// Check if the form is submitted
$campaign_added = false; // Flag to check if the campaign was added successfully
$error_message = ""; // Variable to store any error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $campaign_name = mysqli_real_escape_string($conn, $_POST['campaign_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $goal_amount = mysqli_real_escape_string($conn, $_POST['goal_amount']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    // Validate that the end date is after the start date
    if (!empty($end_date) && $end_date < $start_date) {
        $error_message = "Error: End date cannot be earlier than start date.";
    } else {
        // SQL query to insert campaign into the database
        $sql = "INSERT INTO campaign (campaign_name, description, start_date, end_date, goal_amount, category_id) 
                VALUES ('$campaign_name', '$description', '$start_date', '$end_date', '$goal_amount', '$category_id')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            $campaign_added = true; // Set the flag to true on successful insertion
        } else {
            $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Fetch campaign categories from the database
$categories = mysqli_query($conn, "SELECT * FROM CAMPAIGN_CATEGORY");

// Fetch existing campaigns from the database
$campaigns = mysqli_query($conn, "SELECT campaign.campaign_name, campaign.description, campaign.start_date, campaign.end_date, campaign.goal_amount, CAMPAIGN_CATEGORY.category_name 
                                  FROM campaign 
                                  JOIN CAMPAIGN_CATEGORY ON campaign.category_id = CAMPAIGN_CATEGORY.category_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Campaigns</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // JavaScript function to show the popup if the campaign was added
        function showPopup() {
            alert("New campaign added successfully.");
        }
    </script>
</head>
<body>
    <header>
        <h1>Donation Management</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="donor.php">Donors</a>
            <a href="donation.php">Donations</a>
            <a href="payment.php">Payment</a>
        </nav>
    </header>

    <section>
        <h2>Add New Campaign</h2>
        <?php if (!empty($error_message)): ?>
            <p style="color:red;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="campaign_name">Campaign Name:</label>
            <input type="text" id="campaign_name" name="campaign_name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date">

            <label for="goal_amount">Goal Amount:</label>
            <input type="number" step="0.01" id="goal_amount" name="goal_amount" required>

            <label for="category_id">Campaign Category:</label>
            <select id="category_id" name="category_id" required>
                <?php while ($row = mysqli_fetch_assoc($categories)) : ?>
                    <option value="<?= $row['category_id'] ?>"><?= $row['category_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Add Campaign</button>
        </form>
    </section>

    <section>
        <h2>Existing Campaigns</h2>
        <table>
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Goal Amount</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($campaigns)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['campaign_name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['start_date']) ?></td>
                        <td><?= htmlspecialchars($row['end_date']) ?></td>
                        <td><?= htmlspecialchars($row['goal_amount']) ?></td>
                        <td><?= htmlspecialchars($row['category_name']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <?php if ($campaign_added): ?>
    <script>
        // Call the function to show the popup when the page reloads after a successful campaign addition
        showPopup();
    </script>
    <?php endif; ?>
</body>
</html>
