<?php
/**
 * MICROSERVICE API - api.php
 * Returns ALL employees as JSON.
 * Consumed by the Main System's dropdown (via fetch_api.php proxy).
 *
 * Endpoint: GET http://localhost:81/api.php
 */

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/db_config.php';

try {
    $stmt = $pdo->query("SELECT id, full_name, position, department, email FROM employees ORDER BY full_name ASC");
    $employees = $stmt->fetchAll();

    echo json_encode([
        "status" => "success",
        "count" => count($employees),
        "data" => $employees
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
