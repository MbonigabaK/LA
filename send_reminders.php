<?php
require 'config.php';

// Fetch reminders that haven't been sent
$reminders = $conn->query("SELECT r.id, r.tenant_id, r.message, u.email FROM reminders r JOIN users u ON r.tenant_id = u.id WHERE r.sent_at IS NULL");

while ($reminder = $reminders->fetch_assoc()) {
    $tenant_id = $reminder['tenant_id'];
    $message = $reminder['message'];
    $email = $reminder['email'];

    // Send email (basic PHP mail function, for production use a proper mail library)
    $subject = "Rent Reminder";
    $headers = "From: no-reply@landlordalarm.com";
    if (mail($email, $subject, $message, $headers)) {
        // Mark reminder as sent
        $stmt = $conn->prepare("UPDATE reminders SET sent_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $reminder['id']);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
?>
