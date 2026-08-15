# Cunanan_DockerCodebase — Hotel Reservation Multi-Container Environment

A complete multi-container Docker environment integrating **two independent
systems** via a REST API:

| System | Port | Repo | Description |
|---|---|---|---|
| **Main System** — Hotel Reservation | `80` | [Cunanan_System1](../Cunanan_System1) | Full CRUD app for managing hotel reservations |
| **Microservice** — Employee API | `81` | [Cunanan_System2](../Cunanan_System2) | Provides employee/staff data as JSON |

The Main System's **Create** and **Update** forms have an "Assigned Staff"
dropdown that is populated live from the Microservice's `/api.php` endpoint
(via `main_system/fetch_api.php`, an internal server-to-server proxy).

## Architecture / Services (7 total)

| # | Service | Image | Ports | Purpose |
|---|---|---|---|---|
| 1 | `nginx` | `nginx:alpine` | 80, 81, 443 | Web server — routes 80 → Main System, 81 → Microservice |
| 2 | `php` | built from `php/Dockerfile` (devilbox/php-fpm:8.2-work base) | internal 9000 | App server for Main System |
| 3 | `php_microservice` | built from `php/Dockerfile` | internal 9000 | App server for the Microservice |
| 4 | `mysql` | `mysql:8.0` | 3306 | Database (`hotel_db` + `employee_db`), native auth |
| 5 | `phpmyadmin` | `phpmyadmin/phpmyadmin:latest` | 8080 | Database management UI |
| 6 | `workspace` | `devilbox/php-fpm:8.2-work` | internal | Dev/CLI environment |
| 7 | `redis` | `redis:alpine` | 6379 | Caching layer |

## Folder structure

```
Cunanan_HotelSystem_Website/
├── docker-compose.yml
├── nginx/
│   ├── conf.d/default.conf   # server{80} -> main_system, server{81} -> microservice
│   └── ssl/                  # self-signed cert for :443
├── php/
│   ├── Dockerfile
│   └── conf.d/php.ini
├── mysql/
│   ├── init/init.sql         # creates hotel_db + employee_db, seeds data
│   └── data/                 # persisted MySQL volume
├── redis/data/
├── workspace/
├── main_system/               # System 1 — Port 80
│   ├── index.html
│   ├── style.css
│   ├── script.js
│   ├── db_config.php
│   ├── create.php
│   ├── read.php
│   ├── update.php
│   ├── delete.php
│   └── fetch_api.php          # proxies to Microservice
└── microservice/               # System 2 — Port 81
    ├── index.php
    ├── db_config.php
    ├── api.php                 # returns ALL employees as JSON
    └── get_employees.php       # example: returns ONE employee as JSON
```

## Running it

```bash
docker compose up -d --build
```

- Main System:      http://localhost
- Microservice API: http://localhost:81/api.php
- phpMyAdmin:        http://localhost:8080  (user: root / pass: rootpassword)

## Default credentials

| Service | User | Password |
|---|---|---|
| MySQL root | root | rootpassword |
| MySQL app user | appuser | apppassword |

## Integration flow

1. Browser loads `create.php` (or `update.php`) on the Main System.
2. `script.js` calls `fetch('fetch_api.php')`.
3. `fetch_api.php` (server-side, inside the `php` container) makes an internal
   cURL request to `http://nginx:81/api.php` — the Microservice, reached
   through the shared Docker network.
4. The Microservice's `api.php` queries `employee_db.employees` and returns JSON.
5. `fetch_api.php` relays that JSON back to the browser.
6. `script.js` populates the `<select>` dropdown with the employee list.
7. On submit, the selected `assigned_employee_id` / `assigned_employee_name`
   are stored on the `reservations` row in `hotel_db`.
