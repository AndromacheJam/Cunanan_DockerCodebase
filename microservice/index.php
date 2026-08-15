<?php
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Microservice API</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 60px auto; color: #222; }
        h1 { color: #2c3e50; }
        code { background: #f2f2f2; padding: 2px 6px; border-radius: 4px; }
        .endpoint { background: #f9f9f9; border-left: 4px solid #2c3e50; padding: 12px 16px; margin: 16px 0; }
    </style>
</head>
<body>
    <h1>🧩 Employee Microservice (System 2)</h1>
    <p>This microservice runs on <strong>port 81</strong> and provides employee data to the
       Main Hotel Reservation System (port 80) via a JSON REST API.</p>

    <div class="endpoint">
        <strong>GET</strong> <code>/api.php</code><br>
        Returns the full list of employees. Used to populate the "Assigned Staff" dropdown
        on the Main System's Create and Update forms.
    </div>

    <div class="endpoint">
        <strong>GET</strong> <code>/get_employees.php?id={id}</code><br>
        Example endpoint that returns a single employee's data as JSON.
    </div>

    <p><a href="/api.php">Try /api.php →</a></p>
</body>
</html>
