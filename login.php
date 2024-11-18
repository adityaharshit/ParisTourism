<?php
// Database connection
session_start();
require 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);
    // Check if email or username exists or not
    $checkQuery = "SELECT * FROM users WHERE Email = ? OR Username = ? OR Password = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("sss", $email, $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["UserID"] = $row['UId'];
        $_SESSION["username"] = $row['Username'];
        echo "<script>
        alert('Login successfull');
        window.location.href='explore.html';
        </script>"; 
    } else {
        
        echo "<script>
        alert('Invalid Creadentials');
        window.location.href='login.html';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
