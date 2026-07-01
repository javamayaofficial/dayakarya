# Deploy Dayakarya ke VPS Manual (via SSH)

Panduan khusus **VPS** (mis. Ubuntu 22.04/24.04) dengan Nginx + PHP-FPM + MySQL. Untuk pengguna yang nyaman dengan terminal.

---

## Ringkasan Arsitektur

```
Internet → Nginx (:80/:443) → PHP-FPM 8.2 → Laravel (Dayakarya) → MySQL
                                              ↳ Supervisor → queue:work
                                              ↳ Cron → schedule:run
```

---

## Langkah 1 — Masuk Server & Update

```bash
ssh root@IP_SERVER
apt update && apt upgrade -y
```

---

## Langkah 2 — Pasang Paket

```bash
# Nginx, MySQL, PHP 8.2 + ekstensi
apt install -y nginx mysql-server unzip git curl \
  php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml \
  php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl

# Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

---

## Langkah 3 — Buat Database

```bash
mysql -u root -p
```
```sql
CREATE DATABASE dayakarya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dayakarya_user'@'localhost' IDENTIFIED BY 'PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON dayakarya.* TO 'dayakarya_user'@'localhost';
FLUSH PRIVILEGES; EXIT;
```

---

## Langkah 4 — Clone Repo

```bash
mkdir -p /var/www && cd /var/www
git clone https://github.com/javamayaofficial/dayakarya.git
cd dayakarya
composer install --no-dev --optimize-autoloader
```

---

## Langkah 5 — Environment

```bash
cp .env.example .env
php artisan key:generate
nano .env
```
Isi `APP_URL`, `DB_*`, `DUITKU_*`, `FONNTE_TOKEN`, `MAILKETING_API_TOKEN` (lihat installasi.md).

---

## Langkah 6 — Migrasi, Storage, Perizinan

```bash
php artisan migrate --seed
php artisan storage:link

# Kepemilikan ke user web server
chown -R www-data:www-data /var/www/dayakarya
chmod -R 775 storage bootstrap/cache
```

---

## Langkah 7 — Konfigurasi Nginx

Buat file `/etc/nginx/sites-available/dayakarya`:

```nginx
server {
    listen 80;
    server_name dayakarya.id www.dayakarya.id;
    root /var/www/dayakarya/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }

    client_max_body_size 50M;  # untuk upload audio
}
```

Aktifkan & reload:
```bash
ln -s /etc/nginx/sites-available/dayakarya /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

---

## Langkah 8 — SSL (Let's Encrypt)

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d dayakarya.id -d www.dayakarya.id
```

---

## Langkah 9 — Queue Worker via Supervisor

```bash
apt install -y supervisor
nano /etc/supervisor/conf.d/dayakarya-worker.conf
```
```ini
[program:dayakarya-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dayakarya/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/dayakarya/storage/logs/worker.log
stopwaitsecs=3600
```
```bash
supervisorctl reread
supervisorctl update
supervisorctl start dayakarya-worker:*
```

---

## Langkah 10 — Cron (Scheduler)

```bash
crontab -e
```
Tambahkan:
```
* * * * * cd /var/www/dayakarya && php artisan schedule:run >> /dev/null 2>&1
```

---

## Langkah 11 — Optimasi Produksi

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Alur Update

```bash
cd /var/www/dayakarya
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
supervisorctl restart dayakarya-worker:*   # restart worker agar pakai kode baru
```

---

## Callback Duitku

Set di dashboard Duitku:
```
https://dayakarya.id/api/v1/payments/duitku/callback
```

---

## Checklist VPS

- [ ] Nginx `nginx -t` OK, root ke `/public`
- [ ] PHP-FPM 8.2 jalan
- [ ] `migrate --seed` sukses
- [ ] Permission `storage` & `bootstrap/cache` benar (www-data)
- [ ] SSL aktif
- [ ] Supervisor worker running
- [ ] Cron scheduler aktif
- [ ] Callback Duitku terpasang
