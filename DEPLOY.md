# 🚀 Panduan Deploy Monolink

> Branch `production` hanya berisi hasil build (`.next/`, `public/`, `package.json`, `prisma/`).
> Source code ada di branch `main`.

---

## 📋 Prasyarat Server

| Kebutuhan | Versi |
|-----------|-------|
| Node.js   | ≥ 18  |
| npm       | ≥ 9   |
| MariaDB / MySQL | ≥ 10.6 |
| Git       | ≥ 2   |

---

## 🖥️ Deploy Pertama Kali (Fresh Install)

### 1. Clone branch production

```bash
git clone -b production https://github.com/khairulfikri730/monolink.git
cd monolink
```

### 2. Install dependencies (production only)

```bash
npm install --omit=dev
```

### 3. Buat file `.env`

```bash
cp .env.example .env   # jika ada, atau buat manual
nano .env
```

Isi `.env`:

```env
DATABASE_URL="mysql://USERNAME:PASSWORD@localhost:3306/monolink"
NEXTAUTH_SECRET="isi-dengan-random-string-panjang"
NEXTAUTH_URL="https://domain-anda.com"
```

> Generate `NEXTAUTH_SECRET`:
> ```bash
> node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
> ```

### 4. Buat database

```sql
CREATE DATABASE monolink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'monolink_user'@'localhost' IDENTIFIED BY 'password_aman';
GRANT ALL PRIVILEGES ON monolink.* TO 'monolink_user'@'localhost';
FLUSH PRIVILEGES;
```

### 5. Jalankan migrasi & generate Prisma Client

```bash
npx prisma generate
npx prisma migrate deploy
```

### 6. (Opsional) Seed data awal

```bash
# Admin default: admin@monolink.com / admin123456
node prisma/seed.js
```

> ⚠️ **Segera ganti password admin setelah login pertama!**

### 7. Jalankan server

```bash
npm start
# atau dengan PM2 (direkomendasikan):
pm2 start "npm start" --name monolink
pm2 save
pm2 startup
```

---

## 🔄 Update Deploy (Setelah Ada Perubahan)

### Di mesin development (local):

```powershell
# Dari root project, jalankan deploy script
.\deploy.ps1
```

Script ini otomatis:
1. Build dari branch `main`
2. Push hasil build ke branch `production`

### Di server:

```bash
cd /path/to/monolink

# Pull build terbaru
git pull origin production

# Install dependency baru (jika ada)
npm install --omit=dev

# Generate ulang Prisma Client
npx prisma generate

# Jalankan migrasi baru (jika ada)
npx prisma migrate deploy

# Restart server
pm2 restart monolink
```

---

## ⚙️ Konfigurasi PM2 (Direkomendasikan)

Buat file `ecosystem.config.js` di server:

```js
module.exports = {
  apps: [
    {
      name: 'monolink',
      script: 'node_modules/.bin/next',
      args: 'start',
      env: {
        NODE_ENV: 'production',
        PORT: 3000,
      },
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '500M',
    },
  ],
}
```

```bash
pm2 start ecosystem.config.js
pm2 save
pm2 startup
```

---

## 🌐 Nginx Reverse Proxy (Opsional)

```nginx
server {
    listen 80;
    server_name domain-anda.com www.domain-anda.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }

    # Upload files
    client_max_body_size 10M;
}
```

SSL dengan Certbot:

```bash
sudo certbot --nginx -d domain-anda.com -d www.domain-anda.com
```

---

## 📁 Struktur Branch

| Branch       | Isi                              | Tujuan              |
|--------------|----------------------------------|---------------------|
| `main`       | Source code lengkap              | Development         |
| `production` | `.next/`, `public/`, `prisma/`, `package.json` | Server production |

---

## 🔧 Perintah Berguna di Server

```bash
# Lihat log
pm2 logs monolink

# Monitor
pm2 monit

# Status
pm2 status

# Restart
pm2 restart monolink

# Stop
pm2 stop monolink

# Cek port yang dipakai
ss -tlnp | grep 3000
```

---

## 🗂️ Environment Variables Lengkap

| Variable         | Wajib | Keterangan                            |
|------------------|-------|---------------------------------------|
| `DATABASE_URL`   | ✅    | Connection string MariaDB/MySQL       |
| `NEXTAUTH_SECRET`| ✅    | Secret key untuk session (min 32 char)|
| `NEXTAUTH_URL`   | ✅    | URL publik aplikasi                   |

---

## ⚠️ Catatan Penting

- **Jangan** commit file `.env` ke repository
- **Jangan** edit langsung di branch `production` — selalu dari `main` lalu jalankan `deploy.ps1`
- Folder `uploads/` (jika ada) **tidak** ikut di-deploy — pastikan ada di server dan tidak di-overwrite saat update
- Jalankan `prisma migrate deploy` (bukan `migrate dev`) di server production
