#!/usr/bin/env bash
# Runs Pest locally against the isolated test database only.
# The MariaDB container must be running (it publishes port 3306 on localhost).
# Usage: pnpm run test:pest [artisan test args, e.g. --filter=HomePageTest]
set -euo pipefail

cd "$(dirname "$0")/.."

DB_HOST="${TEST_DB_HOST:-127.0.0.1}"
DB_PORT="${TEST_DB_PORT:-3306}"
DB_NAME="${TEST_DB_DATABASE:-shipping_dog_test}"
DB_USER="${TEST_DB_USERNAME:-shipping_dog_test}"
DB_PASS="${TEST_DB_PASSWORD:-shipping_dog_test_password}"

if [ -z "${TEST_DB_SKIP_PROVISION:-}" ]; then
  docker compose -f ../compose.yml exec -T db mariadb -uroot -p"${MARIADB_ROOT_PASSWORD:-rootpassword}" -e "
    CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASS}';
    GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'%';
    FLUSH PRIVILEGES;"
fi

export APP_KEY="${APP_KEY:-base64:$(php -r 'echo base64_encode(random_bytes(32));')}"
export APP_ENV=testing
export DB_CONNECTION=mysql
export DB_HOST DB_PORT
export DB_DATABASE="$DB_NAME" DB_USERNAME="$DB_USER" DB_PASSWORD="$DB_PASS"
export DB_URL= DATABASE_URL=

# Builds the test schema from database/schema/mysql-schema.sql, then applies newer migrations.
php artisan migrate --force --no-interaction
php artisan test "$@"
