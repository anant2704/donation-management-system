<?php
require_once 'auth_check.php';
check_auth();

// For admin-only pages, also add:
// check_admin();
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Donation Management</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="donor.php">Donors</a>
            <a href="donation.php">Donations</a>
            <a href="campaign.php">Campaigns</a>
            <a href="payment.php">Payment</a>
        </nav>
    </header>

    <section>
        <h2>Welcome to the Donation Management System</h2>
        <p>Manage donors, campaigns, and donations efficiently!</p>

        <h3>Our Mission</h3>
        <p>At Donation Management, we believe in the power of giving. Our platform connects generous donors with impactful campaigns that aim to improve lives and support meaningful causes. Whether you're looking to donate, manage donations, or launch a campaign, we provide the tools you need to make a difference.</p>

        <h3>Why Choose Us?</h3>
        <ul>
            <li><strong>Easy Management:</strong> Our user-friendly interface allows you to manage your donations and campaigns with just a few clicks.</li>
            <li><strong>Real-Time Tracking:</strong> Keep track of your donations and the impact they are making in real-time.</li>
            <li><strong>Secure Payments:</strong> We prioritize your security and ensure that all transactions are safe and secure.</li>
            <li><strong>Community Impact:</strong> Join a community of like-minded individuals who are passionate about giving back.</li>
        </ul>

        <h3>Get Started Today!</h3>
        <p>Ready to make a difference? Explore our campaigns, become a donor, or start your own campaign now!</p>
        <p><a href="donor.php" class="cta-button">Join Us as a Donor</a></p>
        <p><a href="campaign.php" class="cta-button">Launch Your Campaign</a></p>
    </section>

    <footer>
        <p>&copy; <?= date("Y") ?> Donation Management. All rights reserved.</p>
    </footer>
</body>
</html>