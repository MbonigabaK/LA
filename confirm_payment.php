<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenant_id = $_POST['tenant_id'];
    $amount = $_POST['amount'];
    $payment_date = date('Y-m-d');

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payment_history (tenant_id, payment_date, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $tenant_id, $payment_date, $amount);

    if ($stmt->execute()) {
        // Update rent status
        $update_stmt = $conn->prepare("UPDATE tenants SET rent_status = 'paid' WHERE tenant_id = ?");
        $update_stmt->bind_param("i", $tenant_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo "Payment confirmed successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
    exit;
}
?>
