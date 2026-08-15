<?php
require_once __DIR__ . '/db_config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
if (!$id) {
    header("Location: read.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guest_name  = trim($_POST['guest_name'] ?? '');
    $room_number = trim($_POST['room_number'] ?? '');
    $room_type   = trim($_POST['room_type'] ?? '');
    $checkin     = trim($_POST['checkin_date'] ?? '');
    $checkout    = trim($_POST['checkout_date'] ?? '');
    $employee_id = trim($_POST['assigned_employee_id'] ?? '');
    $employee_name = trim($_POST['assigned_employee_name'] ?? '');
    $status      = trim($_POST['status'] ?? 'Pending');

    if ($guest_name === '')  $errors[] = "Guest name is required.";
    if ($room_number === '') $errors[] = "Room number is required.";
    if ($employee_id === '') $errors[] = "Please select an assigned staff member.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE reservations SET
            guest_name = :guest_name,
            room_number = :room_number,
            room_type = :room_type,
            checkin_date = :checkin,
            checkout_date = :checkout,
            assigned_employee_id = :employee_id,
            assigned_employee_name = :employee_name,
            status = :status
            WHERE id = :id");
        $stmt->execute([
            ':guest_name'    => $guest_name,
            ':room_number'   => $room_number,
            ':room_type'     => $room_type,
            ':checkin'       => $checkin,
            ':checkout'      => $checkout,
            ':employee_id'   => $employee_id,
            ':employee_name' => $employee_name,
            ':status'        => $status,
            ':id'            => $id,
        ]);

        header("Location: read.php?msg=Reservation+updated+successfully");
        exit;
    }
}

// Load current record
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = :id");
$stmt->execute([':id' => $id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    header("Location: read.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Reservation - Hotel Reservation System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <h1>🏨 Hotel Reservation System</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="read.php">View Reservations</a>
            <a href="create.php">New Reservation</a>
        </nav>
    </header>

    <main class="container">
        <h2>Edit Reservation #<?= (int)$reservation['id'] ?></h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="update.php" class="form-card">
            <input type="hidden" name="id" value="<?= (int)$reservation['id'] ?>">

            <label for="guest_name">Guest Name</label>
            <input type="text" id="guest_name" name="guest_name" required
                   value="<?= htmlspecialchars($reservation['guest_name']) ?>">

            <label for="room_number">Room Number</label>
            <input type="text" id="room_number" name="room_number" required
                   value="<?= htmlspecialchars($reservation['room_number']) ?>">

            <label for="room_type">Room Type</label>
            <select id="room_type" name="room_type" required>
                <?php foreach (["Standard","Deluxe","Suite","Presidential"] as $type): ?>
                    <option value="<?= $type ?>" <?= $reservation['room_type'] === $type ? 'selected' : '' ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>

            <label for="checkin_date">Check-in Date</label>
            <input type="date" id="checkin_date" name="checkin_date" required
                   value="<?= htmlspecialchars($reservation['checkin_date']) ?>">

            <label for="checkout_date">Check-out Date</label>
            <input type="date" id="checkout_date" name="checkout_date" required
                   value="<?= htmlspecialchars($reservation['checkout_date']) ?>">

            <!-- KEY REQUIREMENT: dropdown populated from Microservice (System 2), pre-selected -->
            <label for="assigned_employee_id">Assigned Staff <small>(loaded from Microservice API)</small></label>
            <select id="assigned_employee_id" name="assigned_employee_id"
                    data-selected="<?= (int)$reservation['assigned_employee_id'] ?>" required>
                <option value="">Loading staff list...</option>
            </select>
            <input type="hidden" id="assigned_employee_name" name="assigned_employee_name"
                   value="<?= htmlspecialchars($reservation['assigned_employee_name']) ?>">

            <label for="status">Status</label>
            <select id="status" name="status">
                <?php foreach (["Pending","Confirmed","Cancelled"] as $st): ?>
                    <option value="<?= $st ?>" <?= $reservation['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">Update Reservation</button>
        </form>
    </main>

    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectEl = document.getElementById('assigned_employee_id');
            const preselectedId = selectEl.getAttribute('data-selected');
            populateEmployeeDropdown('assigned_employee_id', 'assigned_employee_name', preselectedId);
        });
    </script>
</body>
</html>
