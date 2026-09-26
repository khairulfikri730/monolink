# Panduan Deploy Monolink (Laravel 12)

Dokumen ini mendeskripsikan kondisi produksi yang sebenarnya per 2026-09-26.
Versi lama (Next.js/Prisma/PM2, branch `production`, `deploy.ps1`) sudah usang dan
tidak berlaku — source dan rilis sama-sama di branch `main`.

## Peta lingkungan produksi

| Item | Nilai |
|------|-------|
| Server | VPS Ubuntu 24.04 LTS (host `monodev`), 103.123.62.39 — dipakai bersama proyek photobox |
| Runtime | PHP 8.3.6 (php-fpm pool `www`, socket `/run/php/php8.3-fpm.sock`, user `www-data`) |
| Web server | nginx, vhost `/etc/nginx/sites-available/monolink` (symlink ke sites-enabled) |
| Domain | `https://monolink.monodev.id` (certbot/Let's Encrypt, auto-renew, kedaluwarsa ~2026-12-25) |
| Direktori rilis | `/var/www/monolink` — clone git branch `main`, docroot `public/` |
| Database | MariaDB 10.11, database `monolink`, user `monolink`@`localhost` |
| Queue | `QUEUE_CONNECTION=database`, worker supervisord `monolink-queue` (`/etc/supervisor/conf.d/monolink-workers.conf`) |
| Log aplikasi | `storage/logs/laravel-YYYY-MM-DD.log`, `storage/logs/queue-worker.log` |
| Kredensial | `.env` produksi HANYA ada di server (mode 640, root:www-data). Password DB: `/root/.monolink-dbpass` |

Karena satu server dengan photobox: jangan pernah edit vhost/pool/fpm milik proyek itu
untuk keperluan monolink, dan setiap perubahan konfigurasi bersama wajib
`nginx -t` / pengecekan status sebelum reload.

## Alur update rutin (dari laptop development)

```bash
git push origin main
```

Lalu di server (akses lewat SSH key deploy):

```bash
cd /var/www/monolink

# 1. Baca keadaan dulu — jangan buta
git log --oneline -1
git status --porcelain           # harus kosong; kalau kotor, JANGAN reset/checkout
git fetch origin main
git rev-list --count HEAD..origin/main

# 2. Update fast-forward only
git pull --ff-only origin main

# 3. Dependency hanya kalau composer.lock berubah
git diff --name-only ORIG_HEAD HEAD | grep -qx composer.lock && \
  COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader

# 4. Migrasi hanya kalau ada yang tertunda
php artisan migrate --force

# 5. Cache konfigurasi/rute/view selalu diulang setelah kode berubah
php artisan config:cache && php artisan route:cache && php artisan view:cache

# 6. Worker queue harus di-restart agar kode baru dipakai
supervisorctl restart monolink-queue

# 7. Verifikasi
curl -s -o /dev/null -w '%{http_code}\n' https://monolink.monodev.id/up     # 200
curl -s -o /dev/null -w '%{http_code}\n' https://monolink.monodev.id/login   # 200
git log --oneline -1
tail -20 storage/logs/laravel-$(date +%F).log                                # tidak ada ERROR baru
```

Catatan: `php artisan storage:link` sudah dibuat; folder `storage/app/public`
(gambar profil/background) TIDAK boleh dihapus saat update.

## Deploy pertama di server baru (fresh)

1. Prasyarat: PHP >= 8.2 dengan ekstensi `pdo_mysql mbstring curl dom fileinfo intl gd`,
   composer, MariaDB, nginx. Node tidak wajib selama masih pakai Tailwind CDN.
2. `git clone -b main <repo> /var/www/monolink && cd /var/www/monolink`
3. `COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader`
4. Buat DB + user MariaDB khusus, simpan password ke `/root/.monolink-dbpass` (chmod 600).
5. Buat `.env` dari nilai produksi (APP_ENV=production, APP_DEBUG=false,
   APP_URL=https://domain, DB_*, SESSION_DRIVER=database, SESSION_SECURE_COOKIE=true,
   QUEUE_CONNECTION=database, CACHE_STORE=database), lalu `chmod 640` dan
   `chown root:www-data`.
6. `php artisan key:generate --force && php artisan migrate --force && php artisan storage:link`
7. `chown -R root:www-data storage bootstrap/cache` + direktori 775.
8. Vhost nginx seperti file di server: docroot `public/`, `try_files ... /index.php?$query_string`,
   `location ~ \.php$` ke socket fpm, deny dotfiles. `nginx -t` lalu reload.
9. Sertifikat: `certbot --nginx -d domain --non-interactive --agree-tos --redirect`.
10. Supervisor: salin blok `[program:monolink-queue]` dari
    `/etc/supervisor/conf.d/monolink-workers.conf`, `supervisorctl reread && update`.

## Larangan dan kewaspadaan

- Jangan pernah commit/push dari server.
- Jangan timpa rilis dengan upload arsip (server adalah clone git).
- Migrasi yang mengubah data produksi: backup dulu
  (`mysqldump --single-transaction monolink > dump.sql`), dan jalankan satu per satu.
- Jangan restart `php8.3-fpm` atau nginx tanpa reload graceful bila photobox sedang
  menerima traffic; `systemctl reload` selalu didahulukan.
- Jangan hapus artefak insiden/dump tanpa keputusan pemilik.
- Password SSH root sudah pernah tertulis di chat — rotasi dan matikan
  `PasswordAuthentication` di `sshd_config` secepatnya.

## Rollback

```bash
cd /var/www/monolink
git log --oneline -5              # pilih commit sebelumnya
git checkout <commit-lama>        # detached HEAD sementara
php artisan config:cache && php artisan route:cache && php artisan view:cache
supervisorctl restart monolink-queue
```
Rollback DB tidak otomatis — dump sebelum migrasi adalah satu-satunya jaring pengaman.
