# YIDEA PRO — Laravel Marketplace

Website marketplace jasa desain berdasarkan referensi UI YIDEA PRO. Project ini memakai Laravel 12, Blade, Eloquent, MySQL/SQLite, authentication, role access, proposal, chat, membership, dan integrasi pembayaran Midtrans Snap.

## Fitur

### Public
- Landing page
- Browse project + search/filter/pagination
- Directory designer
- Detail project
- Membership pricing

### Authentication
- Register Client / Designer
- Login / Logout
- Password hashing
- Session regeneration
- Role middleware

### Client
- Publish / edit / delete design brief
- Melihat proposal
- Memilih designer
- Chat workspace
- Membayar escrow via Midtrans Snap

### Designer
- Browse brief
- Kirim proposal
- Dashboard proposal & workspace
- Chat setelah dipilih client
- Membership PRO / Studio

### Admin
- Overview user, project, proposal, transaction

### Payment
- Transaction record untuk escrow dan membership
- Midtrans Snap token creation
- Midtrans notification/callback signature verification
- Sandbox/production switch via `.env`

> Untuk pembayaran sungguhan, Anda tetap membutuhkan akun Midtrans sendiri dan mengisi API key. Jangan memasukkan secret key ke Git atau JavaScript/browser.

## Instalasi XAMPP

1. Extract ke `C:\xampp\htdocs\yidea-pro-laravel`
2. Jalankan Apache dan MySQL.
3. Buat database `yidea_pro` melalui phpMyAdmin.
4. Jalankan:

```bash
cd C:\xampp\htdocs\yidea-pro-laravel
composer install
copy .env.example .env
php artisan key:generate
```

5. Atur `.env`:

```env
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yidea_pro
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
```

6. Jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Demo account

- Admin: `admin@yidea.test` / `password`
- Client: `client@yidea.test` / `password`
- Designer: `designer@yidea.test` / `password`

## Midtrans

Untuk sandbox, isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` dari dashboard Midtrans. Set notification URL ke:

`https://DOMAIN-ANDA/payments/midtrans/callback`

Untuk local development, gunakan tunnel seperti Cloudflare Tunnel/ngrok agar Midtrans dapat mengirim webhook ke komputer Anda.

## Struktur data

- users
- memberships
- projects
- proposals
- messages
- transactions
- sessions/cache/jobs

## Catatan produksi

Sebelum deploy, tambahkan HTTPS, queue worker, rate limiting, email verification, upload validation/storage, audit log, webhook idempotency yang lebih ketat, CSRF/webhook strategy sesuai payment provider, dan konfigurasi `APP_DEBUG=false`.
