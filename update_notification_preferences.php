<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $notification_preference = $_POST['notification_preference'];

    $stmt = $conn->prepare("UPDATE users SET notification_preference = ? WHERE id = ?");
    $stmt->bind_param("si", $notification_preference, $user_id);

    if ($stmt->execute()) {
        echo "Notification preferences updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: settings.php");
    exit;
}
?>
