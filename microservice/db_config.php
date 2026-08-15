<?php
/**
 * Database configuration - MICROSERVICE (Employee System)
 * Connects to the employee_db schema on the shared MySQL container.
 */

$DB_HOST = "mysql";           // docker-compose service name
$DB_NAME = "employee_db";
$DB_USER = "appuser";
$DB_PASS = "apppassword";

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}
