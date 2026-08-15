-- ============================================================
-- Cunanan Hotel / Employee Project - Database Initialization
-- Runs automatically on first container start
-- ============================================================

-- ---------- MAIN SYSTEM DATABASE (Hotel Reservation) ----------
CREATE DATABASE IF NOT EXISTS hotel_db;
USE hotel_db;

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100) NOT NULL,
    room_number VARCHAR(10) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    checkin_date DATE NOT NULL,
    checkout_date DATE NOT NULL,
    assigned_employee_id INT NOT NULL,       -- comes from Microservice dropdown
    assigned_employee_name VARCHAR(100) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO reservations
    (guest_name, room_number, room_type, checkin_date, checkout_date, assigned_employee_id, assigned_employee_name, status)
VALUES
    ('Juan Dela Cruz', '101', 'Deluxe', '2026-08-20', '2026-08-22', 1, 'Maria Santos', 'Confirmed'),
    ('Anna Reyes',     '204', 'Standard', '2026-08-21', '2026-08-23', 2, 'Jose Ramirez', 'Pending');

-- ---------- MICROSERVICE DATABASE (Employee System) ----------
CREATE DATABASE IF NOT EXISTS employee_db;
USE employee_db;

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO employees (full_name, position, department, email) VALUES
    ('Maria Santos',   'Front Desk Officer',  'Guest Services', 'maria.santos@hotel.com'),
    ('Jose Ramirez',   'Concierge',           'Guest Services', 'jose.ramirez@hotel.com'),
    ('Liza Fernandez',  'Housekeeping Supervisor', 'Housekeeping', 'liza.fernandez@hotel.com'),
    ('Mark Villanueva', 'Maintenance Technician',  'Facilities',   'mark.villanueva@hotel.com'),
    ('Grace Tolentino', 'Reservations Agent',      'Guest Services', 'grace.tolentino@hotel.com');

-- ---------- Grant app user access to both databases ----------
GRANT ALL PRIVILEGES ON hotel_db.* TO 'appuser'@'%';
GRANT ALL PRIVILEGES ON employee_db.* TO 'appuser'@'%';
FLUSH PRIVILEGES;
