<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'landlord') {
    header("Location: login.php");
    exit;
}

$landlord_id = $_SESSION['user_id'];

// Fetch properties
$properties = $conn->query("SELECT * FROM properties WHERE landlord_id = $landlord_id")->fetch_all(MYSQLI_ASSOC);

// Fetch tenants
$tenants = $conn->query("
    SELECT tenants.id AS tenant_id, users.username AS tenant_username, properties.address, tenants.rent_due_date, tenants.rent_status 
    FROM tenants 
    JOIN users ON tenants.tenant_id = users.id 
    JOIN properties ON tenants.property_id = properties.id 
    WHERE properties.landlord_id = $landlord_id
")->fetch_all(MYSQLI_ASSOC);

// Fetch payment history
$payment_history = $conn->query("
    SELECT ph.*, u.username AS tenant_username 
    FROM payment_history ph 
    JOIN users u ON ph.tenant_id = u.id 
    WHERE u.id IN (SELECT tenant_id FROM tenants WHERE property_id IN (SELECT id FROM properties WHERE landlord_id = $landlord_id))
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Landlord Dashboard</title>
</head>
<body>
    <h1>Landlord Dashboard</h1>
    
    <h2>Properties</h2>
    <ul>
        <?php foreach ($properties as $property): ?>
            <li><?php echo htmlspecialchars($property['address']); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Manage Tenants</h2>
    <form action="manage_tenants.php" method="POST">
        <label for="property_id">Property:</label>
        <select name="property_id" id="property_id">
            <?php foreach ($properties as $property): ?>
                <option value="<?php echo $property['id']; ?>"><?php echo htmlspecialchars($property['address']); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="tenant_id">Tenant ID:</label>
        <input type="text" name="tenant_id" id="tenant_id" required>
        <label for="rent_due_date">Rent Due Date:</label>
        <input type="date" name="rent_due_date" id="rent_due_date" required>
        <button type="submit">Add Tenant</button>
    </form>

    <h2>Tenants</h2>
    <ul>
        <?php foreach ($tenants as $tenant): ?>
            <li>
                <?php echo htmlspecialchars($tenant['tenant_username']) . " - " . htmlspecialchars($tenant['address']) . " - " . htmlspecialchars($tenant['rent_due_date']) . " - " . htmlspecialchars($tenant['rent_status']); ?>
                <form action="update_rent_status.php" method="POST" style="display:inline;">
                    <input type="hidden" name="tenant_id" value="<?php echo $tenant['tenant_id']; ?>">
                    <button type="submit">Confirm Payment</button>
                </form>
                <form action="send_message.php" method="POST" style="display:inline;">
                    <input type="hidden" name="tenant_id" value="<?php echo $tenant['tenant_id']; ?>">
                    <input type="text" name="message" placeholder="Custom message">
                    <button type="submit">Send Message</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Payment History</h2>
    <ul>
        <?php foreach ($payment_history as $payment): ?>
            <li>
                <?php echo htmlspecialchars($payment['payment_date']) . " - " . htmlspecialchars($payment['tenant_username']) . " - $" . htmlspecialchars($payment['amount']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Setup Reminder</h2>
    <form action="setup_reminder.php" method="POST">
        <label for="tenant_id">Tenant ID:</label>
        <input type="text" name="tenant_id" id="tenant_id" required>
        <label for="message">Custom Message (optional):</label>
        <input type="text" name="message" id="message" placeholder="It's time to pay your rent.">
        <button type="submit">Set Reminder</button>
    </form>
</body>
</html>
