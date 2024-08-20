<h2>Setup Reminder</h2>
<form action="setup_reminder.php" method="POST">
    <label for="tenant_id">Tenant ID:</label>
    <input type="text" name="tenant_id" id="tenant_id" required>
    <label for="message">Custom Message (optional):</label>
    <input type="text" name="message" id="message" placeholder="It's time to pay your rent.">
    <button type="submit">Set Reminder</button>
</form>
