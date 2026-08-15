<?php
require_once __DIR__ . '/db_config.php';

$stmt = $pdo->query("SELECT * FROM reservations ORDER BY id DESC");
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservations - Hotel Reservation System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <h1>🏨 Hotel Reservation System</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="read.php" class="active">View Reservations</a>
            <a href="create.php">New Reservation</a>
        </nav>
    </header>

    <main class="container">
        <h2>All Reservations</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest Name</th>
                    <th>Room #</th>
                    <th>Room Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Assigned Staff</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) === 0): ?>
                    <tr><td colspan="9" style="text-align:center;">No reservations found.</td></tr>
                <?php endif; ?>
                <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td><?= htmlspecialchars($r['guest_name']) ?></td>
                    <td><?= htmlspecialchars($r['room_number']) ?></td>
                    <td><?= htmlspecialchars($r['room_type']) ?></td>
                    <td><?= htmlspecialchars($r['checkin_date']) ?></td>
                    <td><?= htmlspecialchars($r['checkout_date']) ?></td>
                    <td><?= htmlspecialchars($r['assigned_employee_name']) ?></td>
                    <td><span class="badge status-<?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                    <td class="actions">
                        <a href="update.php?id=<?= (int)$r['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?= (int)$r['id'] ?>" class="btn btn-delete"
                           onclick="return confirm('Delete this reservation?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
