<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $method_name = $_POST['method_name'];
    $details = $_POST['details'];

    $stmt = $conn->prepare("INSERT INTO payment_methods (user_id, method_name, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $method_name, $details);

    if ($stmt->execute()) {
        echo "Payment method added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: settings.php");
    exit;
}
?>
