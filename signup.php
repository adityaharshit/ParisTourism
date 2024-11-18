<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "skilldev"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if email or username already exists
    $checkQuery = "SELECT * FROM users WHERE Email = ? OR Username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email or username already exists
        $error = "Error: Email or username already exists. Please choose a different one.";
        echo "<script>
        alert('Email or username already exists');
        window.location.href='admin/ahm/panel';
        </script>";
    } else {
        // Proceed with insertion
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO users (Name, Email, Username, Password) VALUES (?, ?, ?, ?)";
        $stmt->prepare($insertQuery);
        $stmt->bind_param("ssss", $name, $email, $username, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>
                alert('Registration successful');
                window.location.href='login.html';
                </script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
