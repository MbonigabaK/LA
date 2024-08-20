<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenant_id = $_POST['tenant_id'];
    $message = $_POST['message'];

    // Fetch tenant email (assuming you have an email column in your users table)
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    // Send email (basic PHP mail function, for production use a proper mail library)
    $subject = "Message from Landlord";
    $headers = "From: no-reply@landlordalarm.com";
    if (mail($email, $subject, $message, $headers)) {
        echo "Message sent successfully.";
    } else {
        echo "Error sending message.";
    }

    $conn->close();
    header("Location: dashboard.php");
    exit;
}
?>
