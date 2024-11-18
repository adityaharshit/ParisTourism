<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <!-- <script src="https://kit.fontawesome.com/871106cf4e.js" crossorigin="anonymous"></script> -->
        <link rel="stylesheet" href="css/responsive.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">

    </nav>





    <!-- Hero Section -->
    <section class="hero hero-login">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="login-card d-flex">
                <div class="login-image">
                    <img src="images/pexels-timea-kadar-860778-2130610.jpg" alt="" srcset="">
                </div>
                <div class="login-form d-flex justify-content-center align-items-center">
                    <div>
                        <h1 class="mb-0">Forgot Password?</h1>
                        <p>Want to sign in? <span class="register-link"><a href="login.html">Sign in</a></span></p>
                        <form action="" method="POST" class="mt-5">
                            <!-- Step 1: Email for sending OTP -->
                            <div class="form-group my-3">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <button type="submit" name="send_otp" class="btn btn-primary login-btn">Send OTP</button>

                            <!-- Step 2: OTP verification -->
                            <div class="form-group my-3">
                                <input type="text" name="otp" id="otp" class="form-control" placeholder="Enter the OTP">
                            </div>
                            <button type="submit" name="verify_otp" class="btn btn-primary login-btn">Verify</button>

                            <!-- Step 3: New password -->
                            <div class="form-group my-3">
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" required disabled>
                            </div>
                            <button type="submit" name="reset_password" class="btn btn-primary login-btn" disabled>Reset</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- Footer section -->
    <div class="custom-footer"></div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.waypoints.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/jquery.barfiller.js"></script>
    <script src="js/index.js"></script>
    
</body>
</html>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'config.php';

session_start();
$conn = new mysqli("localhost", "root", "", "skilldev");

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    // Check if email exists in the users table
    $stmt = $conn->prepare("SELECT UId FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch the UId
        $row = $result->fetch_assoc();
        $UId = $row['UId'];

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in the otp_table
        $stmt = $conn->prepare("INSERT INTO otps(UId, OTP) VALUES (?, ?)");
        $stmt->bind_param("is", $UId, $otp);
        $stmt->execute();

        // Send OTP using PHPMailer
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = $smtpUsername;
        $mail->Password   = $smtpPassword;

        $mail->IsHTML(true);
        $mail->AddAddress($email, "");
        $mail->SetFrom($smtpUsername, "Website Name");
        $mail->Subject = "Your OTP for Password Reset";
        $mail->MsgHTML("Your OTP for resetting your password is: $otp");

        if ($mail->Send()) {
            echo "<script>alert('OTP sent successfully to your email.');</script>";
            $_SESSION['UId'] = $UId;
        } else {
            echo "<script>alert('Failed to send OTP. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please try again.');</script>";
    }
}
    
    if (isset($_POST['verify_otp'])) {
        $otp = $_POST['otp'];
    
        // Retrieve UId from session
        if (isset($_SESSION['UId'])) {
            $UId = $_SESSION['UId'];
    
            // Check if OTP is valid
            $stmt = $conn->prepare("SELECT * FROM otps WHERE UId = ? AND OTP = ?");
            $stmt->bind_param("is", $UId, $otp);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows == 1) {
                echo "<script>
                    document.getElementById('new_password').disabled = false;
                    document.getElementsByName('reset_password')[0].disabled = false;
                    alert('OTP verified. You can now reset your password.');
                </script>";
            } else {
                echo "<script>alert('Invalid OTP. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Session expired. Please try again.');</script>";
        }
    }
    
    
    if (isset($_POST['reset_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
        // Retrieve UId from session
        if (isset($_SESSION['UId'])) {
            $UId = $_SESSION['UId'];
            // Update password in users table
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE UId = ?");
            $stmt->bind_param("si", $new_password, $UId);
            if ($stmt->execute()) {
                echo "<script>alert('Password reset successfully.');</script>";
                $stmt = $conn->prepare("delete from otps where UId = ?");
                $stmt->bind_param("i", $UId);
                $stmt->execute();
                session_destroy();
            } else {
                echo "<script>alert('Error resetting password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Session expired. Please try again.');</script>";
        }
    }
    ?>