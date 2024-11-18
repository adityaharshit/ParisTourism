<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="../css/responsive.css" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
</head>
<body>
    <div class="container mt-5">
        <h2>Add Event</h2>
        <form action="add_event.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter event title" required>
            </div>

            <div class="form-group">
                <label for="date">Event Date</label>
                <input type="date" class="form-control" name="date" id="date" required>
            </div>

            <div class="form-group">
                <label for="time">Event Duration</label>
                <input type="text" class="form-control" name="time" id="time" required>
            </div>

            <div class="form-group">
                <label for="venue">Venue</label>
                <input type="text" class="form-control" name="venue" id="venue" placeholder="Enter venue" required>
            </div>

            <div class="form-group">
                <label for="image">Event Image</label>
                <input type="file" class="form-control-file" name="image" id="image" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
    </div>
</body>
</html>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "skilldev");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = $_POST['venue'];
    
    // Handle image upload
    $targetDir = "uploads/"; // Directory where images will be stored
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . uniqid() . "_" . $imageName; // Unique file path to avoid name collisions
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allow only certain file formats
    $allowedFileTypes = array('jpg', 'png', 'jpeg');
    if (in_array($imageFileType, $allowedFileTypes)) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"],"../".$targetFilePath)) {
            // Insert event data into the database
            $stmt = $conn->prepare("INSERT INTO Events (Title, Date, Time, Venue, Image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $date, $time, $venue, $targetFilePath);

            if ($stmt->execute()) {
                echo "<script>alert('Event added successfully');</script>";
            } else {
                echo "<script>alert('Error adding event');</script>";
            }
        } else {
            echo "<script>alert('Error uploading image');</script>";
        }
    } else {
        echo "<script>alert('Only JPG, JPEG, & PNG files are allowed');</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
