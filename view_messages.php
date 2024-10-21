<?php
session_start(); // Start the session

// Check if the session variable 'xss_success' is set and true
if (!isset($_SESSION['xss_success']) || $_SESSION['xss_success'] !== true) {
    // If no XSS success, deny access to the flag
    echo "<h2>Unauthorized Person!.. Access Denied</h2>";
    exit(); // Stop further execution of the script
}

// Connect to the database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'thm_challenge';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and display the stored messages
$sql = "SELECT fullName, email, subject, message FROM contact_form";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Displaying stored values directly (vulnerable to XSS)
        echo "<h3>Full Name: " . $row['fullName'] . "</h3>";
        echo "<p>Email: " . $row['email'] . "</p>";
        echo "<p>Subject: " . $row['subject'] . "</p>";
        echo "<p>Message: " . $row['message'] . "</p>";
        echo "<hr>";

        // Check if any stored input contains the XSS payload (script tag)
        if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $row['fullName']) || 
            preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $row['email']) || 
            preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $row['subject']) || 
            preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $row['message'])) {

            // Display the CTF flag if the stored XSS payload is found
            echo "<script>alert('XSS Attack Success! Here is Ur flag: THM{congrats_you_found_XSS_flag_for_WS_Assessment}');</script>";
        }
    }
} else {
    echo "No messages found.";
}

// Close the connection
$conn->close();

// Reset session variable to force new XSS payload for future access
unset($_SESSION['xss_success']); // Clear the session & locked again
session_destroy(); // Optionally destroy the session entirely
?>
