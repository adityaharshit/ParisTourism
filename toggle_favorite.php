<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skilldev");

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $EId = $_POST['EId'];
    $UId = 10; // Assuming default user ID
    if (isset($_SESSION['UserID'])) {
        $UId = $_SESSION['UserID']; // If user is logged in
    }

    // Check if the event is already favorited by the user
    $checkFav = $conn->prepare("SELECT * FROM favorites WHERE UId = ? AND EId = ?");
    
    // Check if the prepared statement was successful
    if (!$checkFav) {
        die("Error in SQL query: " . $conn->error); // Output the SQL error for debugging
    }

    $checkFav->bind_param("ii", $UId, $EId);
    $checkFav->execute();
    $favResult = $checkFav->get_result();

    if ($favResult->num_rows > 0) {
        // Event is already favorited, so we unfavorite it
        $deleteFav = $conn->prepare("DELETE FROM favorites WHERE UId = ? AND EId = ?");
        if (!$deleteFav) {
            die("Error in SQL query: " . $conn->error); // Output the SQL error for debugging
        }
        $deleteFav->bind_param("ii", $UId, $EId);
        if ($deleteFav->execute()) {
            echo json_encode(["status" => "unfavorited"]);
        }
    } else {
        // Event is not yet favorited, so we favorite it
        $insertFav = $conn->prepare("INSERT INTO favorites (UId, EId) VALUES (?, ?)");
        if (!$insertFav) {
            die("Error in SQL query: " . $conn->error); // Output the SQL error for debugging
        }
        $insertFav->bind_param("ii", $UId, $EId);
        if ($insertFav->execute()) {
            echo json_encode(["status" => "favorited"]);
        }
    }
}
?>
