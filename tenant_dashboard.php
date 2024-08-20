<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tenant') {
    header("Location: login.php");
    exit;
}

$tenant_id = $_SESSION['user_id'];

// Fetch rent status
$rent_status = $conn->query("SELECT * FROM tenants WHERE tenant_id = $tenant_id")->fetch_assoc();

// Fetch payment history
$payment_history = $conn->query("SELECT * FROM payment_history WHERE tenant_id = $tenant_id")->fetch_all(MYSQLI_ASSOC);

// Fetch notifications
$notifications = $conn->query("SELECT * FROM notifications WHERE tenant_id = $tenant_id")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tenant Dashboard</title>
</head>
<body>
    <h1>Tenant Dashboard</h1>

    <h2>Rent Status</h2>
    <?php if ($rent_status): ?>
        <p>Rent Due Date: <?php echo htmlspecialchars($rent_status['rent_due_date']); ?></p>
        <p>Rent Status: <?php echo htmlspecialchars($rent_status['rent_status']); ?></p>
    <?php else: ?>
        <p>No rent information available.</p>
    <?php endif; ?>

    <h2>Notifications</h2>
    <ul>
        <?php foreach ($notifications as $notification): ?>
            <li>
                <?php echo htmlspecialchars($notification['message']); ?>
                <form action="mark_notification_read.php" method="POST" style="display:inline;">
                    <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                    <button type="submit">Mark as Read</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Payment History</h2>
    <ul>
        <?php foreach ($payment_history as $payment): ?>
            <li><?php echo htmlspecialchars($payment['payment_date']) . " - $" . htmlspecialchars($payment['amount']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
