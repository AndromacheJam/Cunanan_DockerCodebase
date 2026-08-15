<?php
/**
 * fetch_api.php
 * Server-side proxy that calls the MICROSERVICE (System 2 - Employee API)
 * and relays the JSON back to the browser. Used by create.php / update.php
 * to populate the "Assigned Staff" dropdown.
 *
 * Internally, the two systems share the same docker network, so we can
 * reach the microservice through the nginx container on port 81.
 */

header("Content-Type: application/json; charset=utf-8");

// Internal docker-network address of the Microservice (System 2)
$MICROSERVICE_URL = "http://nginx:81/api.php";

$ch = curl_init($MICROSERVICE_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    http_response_code(502);
    echo json_encode([
        "status" => "error",
        "message" => "Could not reach Microservice API: " . ($error ?: "HTTP $httpCode")
    ]);
    exit;
}

// Relay the microservice's JSON response as-is
echo $response;
