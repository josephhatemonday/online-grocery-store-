<?php
include_once("connection.php");
include_once("functions.php");

if (!isAdmin()) {
    header("Location: dashboard.php");
    exit();
}

$logs = $conn->query("
    SELECT l.log_time, u.name, l.action 
    FROM AccessLogs l 
    JOIN Users u ON l.user_id = u.user_id 
    ORDER BY l.log_time DESC
");
?>

<h2>Access Logs</h2>
<table border="1">
    <tr><th>Time</th><th>User</th><th>Action</th></tr>
    <?php while ($log = $logs->fetch_assoc()): ?>
        <tr>
            <td><?= $log['log_time'] ?></td>
            <td><?= htmlspecialchars($log['name']) ?></td>
            <td><?= htmlspecialchars($log['action']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>