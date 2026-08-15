<?php
require_once __DIR__ . '/db_config.php';

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
    if ($checkin === '' || $checkout === '') $errors[] = "Check-in and check-out dates are required.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO reservations
            (guest_name, room_number, room_type, checkin_date, checkout_date, assigned_employee_id, assigned_employee_name, status)
            VALUES (:guest_name, :room_number, :room_type, :checkin, :checkout, :employee_id, :employee_name, :status)");
        $stmt->execute([
            ':guest_name'   => $guest_name,
            ':room_number'  => $room_number,
            ':room_type'    => $room_type,
            ':checkin'      => $checkin,
            ':checkout'     => $checkout,
            ':employee_id'  => $employee_id,
            ':employee_name'=> $employee_name,
            ':status'       => $status,
        ]);

        header("Location: read.php?msg=Reservation+created+successfully");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Reservation - Hotel Reservation System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <h1>🏨 Hotel Reservation System</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="read.php">View Reservations</a>
            <a href="create.php" class="active">New Reservation</a>
        </nav>
    </header>

    <main class="container">
        <h2>Create New Reservation</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="create.php" class="form-card">
            <label for="guest_name">Guest Name</label>
            <input type="text" id="guest_name" name="guest_name" required
                   value="<?= htmlspecialchars($_POST['guest_name'] ?? '') ?>">

            <label for="room_number">Room Number</label>
            <input type="text" id="room_number" name="room_number" required
                   value="<?= htmlspecialchars($_POST['room_number'] ?? '') ?>">

            <label for="room_type">Room Type</label>
            <select id="room_type" name="room_type" required>
                <option value="Standard">Standard</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Suite">Suite</option>
                <option value="Presidential">Presidential</option>
            </select>

            <label for="checkin_date">Check-in Date</label>
            <input type="date" id="checkin_date" name="checkin_date" required>

            <label for="checkout_date">Check-out Date</label>
            <input type="date" id="checkout_date" name="checkout_date" required>

            <!-- KEY REQUIREMENT: dropdown populated from Microservice (System 2) via fetch_api.php -->
            <label for="assigned_employee_id">Assigned Staff <small>(loaded from Microservice API)</small></label>
            <select id="assigned_employee_id" name="assigned_employee_id" required>
                <option value="">Loading staff list...</option>
            </select>
            <input type="hidden" id="assigned_employee_name" name="assigned_employee_name">

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <button type="submit" class="btn btn-primary">Save Reservation</button>
        </form>
    </main>

    <script src="script.js"></script>
    <script>
        // Populate the staff dropdown from the Microservice API on page load
        document.addEventListener('DOMContentLoaded', () => {
            populateEmployeeDropdown('assigned_employee_id', 'assigned_employee_name');
        });
    </script>
</body>
</html>
