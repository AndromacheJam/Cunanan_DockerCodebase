# Workspace Container

This directory is mounted into the `workspace` service (devilbox/php-fpm:8.2-work),
which provides a development/CLI environment with access to the entire project
(`/shared/httpd`). Use it to run composer, artisan-style CLI tasks, database
migrations, or ad-hoc PHP scripts without installing PHP on the host machine.

Example:
```
docker compose exec workspace bash
php -v
mysql -h mysql -u appuser -p hotel_db
```
