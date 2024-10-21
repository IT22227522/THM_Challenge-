<?php

$conn = new mysqli('localhost', 'root', '', 'thm_challenge'); // Adjust credentials as needed


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        //CTF statement
        $flag = "THM{congrats_you_found_loginBypass_flag_for_WS Assesment}";
        echo "<script type='text/javascript'>alert('Congratulations! Here is your CTF flag: $flag');</script>";

        
        echo "<h2>Login successful! Welcome, $username.</h2>";
        
        
    } else {
        
        echo "Invalid username or password.";
    }
}
$conn->close();
?>
