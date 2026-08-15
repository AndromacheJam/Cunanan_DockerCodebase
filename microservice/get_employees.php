<?php
/**
 * MICROSERVICE API - get_employees.php
 * Example endpoint: returns a SINGLE employee's data as JSON.
 *
 * Endpoint: GET http://localhost:81/get_employees.php?id=1
 */

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/db_config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required parameter: id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, full_name, position, department, email FROM employees WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $employee = $stmt->fetch();

    if (!$employee) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Employee not found"]);
        exit;
    }

    echo json_encode(["status" => "success", "data" => $employee], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
