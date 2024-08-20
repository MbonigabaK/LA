<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notification_id = $_POST['notification_id'];

    $stmt = $conn->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
    $stmt->bind_param("i", $notification_id);

    if ($stmt->execute()) {
        echo "Notification marked as read.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: tenant_dashboard.php");
    exit;
}
?>
