<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$payment_methods = $conn->query("SELECT * FROM payment_methods WHERE user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
</head>
<body>
    <h1>Settings</h1>

    <h2>Profile Management</h2>
    <form action="update_profile.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <button type="submit">Update Profile</button>
    </form>

    <h2>Notification Preferences</h2>
    <form action="update_notification_preferences.php" method="POST">
        <label for="notification_preference">Notification Preference:</label>
        <select name="notification_preference" id="notification_preference">
            <option value="email" <?php if ($user['notification_preference'] == 'email') echo 'selected'; ?>>Email</option>
            <option value="sms" <?php if ($user['notification_preference'] == 'sms') echo 'selected'; ?>>SMS</option>
            <option value="none" <?php if ($user['notification_preference'] == 'none') echo 'selected'; ?>>None</option>
        </select>
        <button type="submit">Update Preferences</button>
    </form>

    <h2>Payment Methods</h2>
    <form action="add_payment_method.php" method="POST">
        <label for="method_name">Payment Method:</label>
        <input type="text" name="method_name" id="method_name" required>
        <label for="details">Details:</label>
        <textarea name="details" id="details" required></textarea>
        <button type="submit">Add Payment Method</button>
    </form>

    <h3>Existing Payment Methods</h3>
    <ul>
        <?php foreach ($payment_methods as $method): ?>
            <li><?php echo htmlspecialchars($method['method_name']) . ": " . htmlspecialchars($method['details']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
