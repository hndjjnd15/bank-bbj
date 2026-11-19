# AGENTS.md

## Project
Laravel 12 + Filament 5 bank website. Public site is server-rendered Blade with hand-written CSS/JS (no Vite). Admin panel is Filament at `/admin`. UI copy, validation messages, and most identifiers/comments are Indonesian — keep them that way.

## Commands
- `composer install` then `npm install` for a fresh checkout.
- `npm run build` — builds the Filament admin theme. Required after editing `resources/css/filament/admin/theme.css` or on a fresh clone.
- `php artisan serve` — public site + `/admin`. `composer dev` runs server, queue listener, Pail logs, and Vite together.
- `php artisan migrate` — run migrations.
- Content seeders are **not** called by `DatabaseSeeder` (it only creates `test@example.com`). Run them explicitly, e.g. `php artisan db:seed --class=BeritaSeeder`, `--class=ProdukDepositoSeeder`, `--class=KreditSeeder`, etc.
- `composer test` or `php artisan test`; single test: `php artisan test --filter=ExampleTest`. Tests use in-memory SQLite via `phpunit.xml`, so MySQL is not needed.
- `./vendor/bin/pint` for formatting. There is no CI or lint pipeline.

## Architecture
- Public flow: `routes/web.php` → `app/Http/Controllers/*` → `app/Models/*` → `resources/views/*`.
- Admin: Filament resources under `app/Filament/Resources/` are auto-discovered. Form/table logic lives in each resource's `Schemas/*Form.php` and `Tables/*Table.php`. Navigation grouping/order is in `app/Providers/Filament/AdminPanelProvider.php`; only add a resource there if it needs a menu entry.
- Homepage banners are managed through the `BannerBeranda` resource in **Informasi → Banner Beranda**. Active slides are ordered by `urutan`, use their `durasi_detik`, and support separate desktop/mobile images on the public disk; if none are active, the homepage uses the fallback in `config/beranda.php`.
- FAQ: `App\Models\Faq` + `FaqResource`. `halaman` picks the placement (`beranda`, `deposito`, `tabungan`, `kredit`); credit FAQs also set `produk_bank_id` and only appear on that product page. `/faq` groups all published entries and shows a question only once even when it is placed on several pages; the homepage shows up to 6 entries tagged `beranda`.
- Site settings: `App\Models\PengaturanSitus` is a single row holding the floating WhatsApp button (number, prefilled message, on/off). Edit it at `/admin/pengaturan-situs`; the strip's WhatsApp link reuses the same number. The partial `partials/whatsapp-mengambang` and `public/css/style.css` (`.wa-mengambang`) render the button.
- Pengajuan kredit: `GET/POST /pengajuan-kredit` → `PengajuanKreditController` (`app/Http/Requests/PengajuanKreditRequest.php` holds the conditional rules) → `App\Models\PengajuanKredit` → `resources/views/pengajuan-kredit/index.blade.php` + `sukses.blade.php`. Applications are stored in MySQL (`pengajuan_kredits`): all form fields as encrypted JSON in `data`, document metadata in `berkas`, files on the private `local` disk under `pengajuan-kredit/{kode}`. Admin follow-up (status/catatan, document links, ZIP download) is the `PengajuanKredits` resource under "Kotak Masuk".
- Pengajuan kredit notifications: the internal email to `PENGAJUAN_KREDIT_EMAIL_TUJUAN` carries every uploaded document as an attachment. The NIK is shown in full by default; the admin can optionally mask it in **Pengaturan Situs**. The applicant only gets the ticket confirmation (`PengajuanKreditDiterima`), without attachments. The "Buka di panel admin" button is optional (`PENGAJUAN_KREDIT_LINK_ADMIN=false` by default). If the mail server rejects the attachment size, the notifier retries once without attachments and logs a warning. The WhatsApp message links to the credit webmail (`PENGAJUAN_KREDIT_URL_EMAIL`).
- Site content, form options, and notification recipients live in `config/deposito.php`, `config/pengaduan.php`, `config/ppid.php`, `config/pengajuan-kredit.php`, `config/wbs.php` (fed by env vars). Change config/env instead of hardcoding values in views.
- Notifications: `app/Services/*Notifier.php` + `app/Mail/*` + `WhatsAppSender`. Manual WhatsApp checks: `php artisan pengaduan:tes-wa`, `php artisan ppid:tes-wa`, `php artisan wbs:tes-wa`.
- Text alignment helper: `app/Support/Perataan.php`. Map coordinate trait: `app/Models/Concerns/MemilikiPeta.php`.

## Gotchas
- The public site does **not** use Vite. `resources/views/layouts/app.blade.php` loads `public/css/style.css` and `public/js/script.js` directly. Edit those files for public UI changes; `resources/css/app.css`, `resources/js/app.js`, and `resources/js/bootstrap.js` are unused Laravel defaults.
- Admin styling goes through `->viteTheme('resources/css/filament/admin/theme.css')`, so theme edits need `npm run build` (or `npm run dev`).
- Uploads use the `public` disk (`storage/app/public`). Run `php artisan storage:link` on a fresh setup. Exception: pengajuan-kredit documents go to the private `local` disk and are only reachable through the admin-guarded routes `berkas-pengajuan-kredit/*`.
- `bootstrap/app.php` redirects web 404s to the referer (or `/`) instead of rendering a 404 page. Debug missing routes/records via logs and controllers, not by expecting a 404 screen.
- The active DB is whatever `.env` says; currently MySQL with `DB_DATABASE=bank_bbj`. `database/database.sqlite` and the root `bank_bbj` file are not the live database.
- Dead/stray code to avoid building on: `resources/views/welcome.blade.php`, `public/images/web-benner-*.webp`, the 0-byte root file `get()`, and the root SQLite file `bank_bbj`.
- `/deposito` is rendered by `ProdukController::deposito`. The old flyer PDF route (`deposito.flyer` / `DepositoController::downloadFlyer`) has been removed; flyer images uploaded via the admin panel are still shown in the product sidebars. `barryvdh/laravel-dompdf` remains installed but is currently unused.
- `README.md` is the default Laravel readme, not project documentation.
