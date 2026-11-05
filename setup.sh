#!/usr/bin/env bash
# Setup awal untuk semua anggota tim. Jalankan dari folder proyek.
#   bash setup.sh          -> SQLite (cepat, tanpa memasang MySQL)
#   bash setup.sh mysql    -> MySQL sesuai PPT (buat dulu database "pengukur_kebodohan")
set -e

MODE="${1:-sqlite}"

composer install
[ -f .env ] || cp .env.example .env
php artisan key:generate

set_env() {  # set_env KUNCI NILAI
  if grep -q "^$1=" .env; then
    sed -i.bak "s|^$1=.*|$1=$2|" .env && rm -f .env.bak
  else
    echo "$1=$2" >> .env
  fi
}

if [ "$MODE" = "mysql" ]; then
  set_env DB_CONNECTION mysql
  set_env DB_HOST 127.0.0.1
  set_env DB_PORT 3306
  set_env DB_DATABASE pengukur_kebodohan
  set_env DB_USERNAME root
  set_env DB_PASSWORD ""
  echo "Memakai MySQL. Pastikan database 'pengukur_kebodohan' sudah dibuat dan .env sesuai."
else
  mkdir -p database
  touch database/database.sqlite
  set_env DB_CONNECTION sqlite
  # SQLite tidak butuh pengaturan host/port/nama/user/password
  sed -i.bak -E '/^DB_(HOST|PORT|DATABASE|USERNAME|PASSWORD)=/d' .env && rm -f .env.bak
fi

php artisan migrate:fresh --seed
php artisan config:clear

echo
echo "Selesai. Jalankan: php artisan serve"
