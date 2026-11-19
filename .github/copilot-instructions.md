# Copilot instructions for this repository

## Project overview

This is a Laravel 12 + Filament 5 bank website. The public site is server-rendered Blade with handwritten CSS/JS, while the admin area runs through Filament at `/admin`.

Most user-facing copy, validation messages, and comments are in Indonesian. Keep terminology and wording consistent with the repo.

## Build, test, and lint commands

Fresh setup:
- `composer install`
- `npm install`
- `php artisan storage:link` on a fresh environment if uploads are needed

Common local commands:
- `npm run build` — rebuild the Filament admin theme; required after editing `resources/css/filament/admin/theme.css` or on a fresh clone
- `php artisan serve` — starts the web app and admin area
- `composer dev` — runs the app, queue worker, Pail logs, and Vite together
- `php artisan migrate` — apply database migrations
- `composer test` or `php artisan test` — run the test suite
- `php artisan test --filter=PengajuanKreditTest` — run one test class
- `php artisan test --filter=test_nik_di_email_internal_bisa_disamarkan_dari_pengaturan_situs` — run one test method
- `vendor/bin/pint` — format PHP code; add `--test` to check formatting without changing files

Notes:
- Seeders are not auto-run by `DatabaseSeeder`; it only creates `test@example.com`.
- Use explicit commands like `php artisan db:seed --class=BeritaSeeder` or `php artisan db:seed --class=ProdukDepositoSeeder` when you need seeded content.
- `php artisan db:seed --class=KreditSeeder` adds the six initial credit product entries without overwriting existing admin edits.
- Tests use in-memory SQLite configured in `phpunit.xml`; MySQL is not required for the test suite.

## High-level architecture

Public site flow:
- `routes/web.php` -> `app/Http/Controllers/*` -> `app/Models/*` -> `resources/views/*`
- Product pages share `ProdukBank`, distinguished by `jenis` (`deposito`, `tabungan`, or `kredit`) and `diterbitkan`; `ProdukController` supplies the product-specific listing/detail views.
- Public content pages generally query their Eloquent models in controllers; form choices, notification recipients, and other deployment-specific values come from `config/*.php` backed by environment variables.

Admin flow:
- Filament resources live under `app/Filament/Resources/`
- Form and table logic is split per resource into `Schemas/*Form.php` and `Tables/*Table.php`
- Navigation grouping/order is defined in `app/Providers/Filament/AdminPanelProvider.php`
- New resources are auto-discovered; only add them to the panel provider if they need a menu entry
- Homepage banners use `BannerBeranda` records managed under **Informasi → Banner Beranda**. Active slides are ordered by `urutan`, rotate using each slide's `durasi_detik`, and can have separate desktop/mobile uploads on the public disk. If no active slides exist, the homepage falls back to `config/beranda.php`.
- FAQs are managed through the `Faq` model and Filament FAQ resource; `halaman` controls placement, and credit FAQs can optionally target a `ProdukBank` record through `produk_bank_id`. The public `/faq` page groups all published entries.
- Credit products use `ProdukBank` rows with `jenis=kredit`; `/kredit` lists published entries and `/kredit/{slug}` renders details. The product form controls the optional calculator, rate, admin fee, brochure, and WhatsApp contact.

Configuration and content:
- Site content, form options, and notification recipients are in `config/deposito.php`, `config/pengaduan.php`, `config/ppid.php`, `config/pengajuan-kredit.php`, and `config/wbs.php`
- Prefer changing config/env values rather than hardcoding values in views or controllers
- Use the database-backed `PengaturanSitus` for settings that administrators edit in the panel; keep deployment secrets and notification endpoints in environment-backed config.
- Credit applications posted to `/pengajuan-kredit` are stored in MySQL through `App\Models\PengajuanKredit` (encrypted `data` JSON, document metadata in `berkas`, files on the private `local` disk). Admin follow-up is the `PengajuanKredits` resource under "Kotak Masuk", which also offers per-file and ZIP document downloads.
- The internal credit email attaches every document; the applicant only receives the ticket confirmation. NIK masking in the internal email is optional and controlled in **Pengaturan Situs** (off by default); it does not alter attached documents. `PENGAJUAN_KREDIT_LINK_ADMIN` toggles the "Buka di panel admin" button (off by default) and `PENGAJUAN_KREDIT_URL_EMAIL` sets the webmail link used in the WhatsApp message.

Notifications and integrations:
- Notification logic lives in `app/Services/*Notifier.php`, `app/Mail/*`, and `WhatsAppSender`
- Useful manual checks: `php artisan pengaduan:tes-wa`, `php artisan ppid:tes-wa`, `php artisan wbs:tes-wa`

Helpful support code:
- `app/Support/Perataan.php` handles text alignment
- `app/Models/Concerns/MemilikiPeta.php` handles map coordinate data

## Key conventions and repo-specific gotchas

- The public site does not use Vite. `resources/views/layouts/app.blade.php` loads `public/css/style.css` and `public/js/script.js` directly.
  - Edit those files for public UI changes.
  - `resources/css/app.css`, `resources/js/app.js`, and `resources/js/bootstrap.js` are unused default Laravel files.
- Admin styling goes through `->viteTheme('resources/css/filament/admin/theme.css')`, so theme edits require `npm run build` (or `npm run dev`).
- Uploads use the `public` disk (`storage/app/public`). Run `php artisan storage:link` on a fresh setup.
- `bootstrap/app.php` redirects web 404s to the referer or `/` instead of rendering a dedicated 404 page; debug missing routes or records via logs/controllers rather than expecting a 404 page.
- The active database is whatever `.env` says. In this repo, the live database is not `database/database.sqlite` or the root `bank_bbj` file.
- Avoid building on dead or stray code such as `resources/views/welcome.blade.php`, `public/images/web-benner-*.webp`, the 0-byte root file `get()`, and the root SQLite file `bank_bbj`.
- `/deposito` is rendered by `ProdukController::deposito`. The flyer PDF route (`deposito.flyer` / `DepositoController::downloadFlyer`) has been removed; uploaded flyer images still appear in the product sidebars, and `barryvdh/laravel-dompdf` is no longer used.
- `README.md` is the default Laravel readme and is not the project documentation.

## Working style for this codebase

- Keep UI and validation copy in Indonesian to match the application.
- Prefer repository conventions and config values over duplicated literals.
- For Filament work, patch the relevant resource’s schemas/tables and keep navigation intent in the panel provider only when necessary.
- When touching public CSS/JS, keep the hand-written server-rendered approach in mind; do not assume Vite is used for those assets.
- When adding or modifying functionality with external notifications, check the corresponding config and service pattern before hardcoding recipients or messages.
