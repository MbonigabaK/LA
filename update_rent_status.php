<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenant_id = $_POST['tenant_id'];

    $stmt = $conn->prepare("UPDATE tenants SET rent_status = 'paid' WHERE id = ?");
    $stmt->bind_param("i", $tenant_id);

    if ($stmt->execute()) {
        echo "Rent status updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
    exit;
}
?>
