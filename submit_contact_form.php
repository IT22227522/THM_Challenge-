<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the inputs from the form
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

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

    // Escape input to prevent SQL syntax errors
    $fullName = mysqli_real_escape_string($conn, $fullName);
    $email = mysqli_real_escape_string($conn, $email);
    $subject = mysqli_real_escape_string($conn, $subject);
    $message = mysqli_real_escape_string($conn, $message);

    // Insert the escaped data into the database
    $sql = "INSERT INTO contact_form (fullName, email, subject, message) 
            VALUES ('$fullName', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<h2>Your message has been received!.. We will get back to you soon :)</h2>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();

    // Set the session variable to allow access to view page after XSS payload
    if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $fullName) || 
        preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $email) || 
        preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $subject) || 
        preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $message)) {

        // If XSS payload is detected, set a session variable
        $_SESSION['xss_success'] = true; // Allow access to view page
    } else {
        // If no XSS payload, deny access to view page
        $_SESSION['xss_success'] = false;
    }
}
?>
