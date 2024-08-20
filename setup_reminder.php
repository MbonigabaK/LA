<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $landlord_id = $_SESSION['user_id'];
    $tenant_id = $_POST['tenant_id'];
    $message = !empty($_POST['message']) ? $_POST['message'] : 'It\'s time to pay your rent.';

    $stmt = $conn->prepare("INSERT INTO reminders (landlord_id, tenant_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $landlord_id, $tenant_id, $message);

    if ($stmt->execute()) {
        echo "Reminder setup successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboar.php");
    exit;
}
?>
