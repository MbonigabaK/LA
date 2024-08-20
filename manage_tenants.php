<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_id = $_POST['property_id'];
    $tenant_id = $_POST['tenant_id'];
    $rent_due_date = $_POST['rent_due_date'];

    $stmt = $conn->prepare("INSERT INTO tenants (property_id, tenant_id, rent_due_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $property_id, $tenant_id, $rent_due_date);

    if ($stmt->execute()) {
        echo "Tenant added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
    exit;
}
?>
