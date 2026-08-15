<?php
require_once __DIR__ . '/db_config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: read.php?msg=Reservation+deleted+successfully");
    exit;
}

header("Location: read.php");
exit;
