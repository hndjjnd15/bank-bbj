# AGENTS.md

## Project
Laravel 12 + Filament 5 bank website (Bank BBJ). Public pages are server-rendered Blade with hand-written CSS/JS; the admin panel is Filament at `/admin`. UI copy, validation messages, and most identifiers/comments are Indonesian — keep them that way.

## Commands
- Fresh setup: `composer install`, `npm install`, `php artisan storage:link` (needed for public uploads). `composer setup` wraps env/key/migrate/npm but runs `migrate --force`.
- `npm run build` — rebuilds the Filament admin theme (`resources/css/filament/admin/theme.css`). Public CSS/JS needs no build.
- `php artisan serve` — public site and `/admin`. `composer dev` also runs the queue listener, Pail, and Vite.
- `php artisan migrate`.
- Content seeders are **not** run by `DatabaseSeeder` (it only creates `test@example.com`, factory password `password`). Run them explicitly, e.g. `php artisan db:seed --class=BeritaSeeder`; others: `KreditSeeder`, `ProdukDepositoSeeder`, `LelangSeeder`, `LowonganSeeder`, `LaporanSeeder`, `ProfilSeeder`, `ProgramCsrSeeder`, `KantorCabangSeeder`, `DokumenPpidSeeder`.
- `composer test` (clears config, then runs tests) or `php artisan test`; single test: `php artisan test --filter=PengajuanKreditTest`. Tests use in-memory SQLite from `phpunit.xml`; MySQL is not needed.
- `php vendor/bin/pint` formats PHP (`--test` checks only). There is no CI or lint pipeline.
- Manual WhatsApp checks: `php artisan pengaduan:tes-wa`, `ppid:tes-wa`, `wbs:tes-wa`.

## Architecture
- Public flow: `routes/web.php` → `app/Http/Controllers/*` → `app/Models/*` → `resources/views/*`. `/produk` is a static Blade view; the rest are controller-driven.
- Products use one model, `ProdukBank`, with `jenis` = `deposito|tabungan|kredit` and `diterbitkan`. `/tabungan` and `/kredit` list published rows with `/{slug}` detail pages; `/deposito` is one page built from the first published deposito (404 if none). Rates live in `SukuBungaDeposito` (`produk_bank_id`, with `tenor_bulan` unique per product) and are also used for tabungan; the admin label is "Suku Bunga Produk".
- Credit products' `deskripsi` is rich HTML edited via the virtual `deskripsi_kredit` field and must be rendered through `HtmlDeskripsiProduk::bersihkan()`; other product types use plain-text `deskripsi`.
- Other public pages: `/berita`, `/lelang`, `/jaringan-kantor`, `/csr`, `/profil-perusahaan`, `/laporan`, `/karir`, `/faq`; forms at `/pengajuan-kredit`, `/pengaduan`, `/wbs`, `/ppid/permohonan`.
- Admin: resources are auto-discovered from `app/Filament/Resources/`, with form/table logic split into `Schemas/*Form.php` and `Tables/*Table.php`. A resource appears in the sidebar only if listed in the navigation builder in `app/Providers/Filament/AdminPanelProvider.php` (groups: **Kotak Masuk, Produk, Informasi, Tentang Kami, Pengaturan**). Dashboard widgets live in `app/Filament/Widgets`; custom login in `app/Filament/Auth/Login.php`.
- Admin visual specs: `.github/desain-portal-admin/` (also referenced at the top of `theme.css`).
- Homepage banners: `BannerBeranda` under Informasi; active slides order by `urutan`, rotate per `durasi_detik`, and use separate desktop/mobile images on the public disk. `BerandaController` falls back to `config/beranda.php` when the table is missing or empty.
- FAQ: `halaman` = `beranda|deposito|tabungan|kredit`. Credit FAQs also set `produk_bank_id` and only appear on that product. `/faq` groups published entries and dedupes by question+answer (product placement wins over `beranda`); the homepage merges admin `beranda` FAQs with the `config/beranda.php` fallback, capping at 7 unique questions.
- Site settings: `PengaturanSitus` is a single row (floating WhatsApp number/message/on-off, credit NIK masking, and the page-loading GIF + duration). Read it through `PengaturanSitus::saatIni()`, which caches the row and returns `null` before the table exists; edit at `/admin/pengaturan-situs`.
- Submission pipelines (`pengajuan-kredit`, `pengaduan`, `wbs`, `ppid`): controller → model → `app/Services/*Notifier.php` + `app/Mail/*` + `WhatsAppSender`. All public forms have a hidden `website` honeypot and a 5-per-minute throttle. Attachments (credit, pengaduan, WBS) go to the private `local` disk (`storage/app/private`), never the public disk; the admin download routes are `berkas-pengajuan-kredit/*`, `lampiran-pengaduan/*`, `lampiran-wbs/*` and require `auth`.
- Notifications run after the response (`dispatch(...)->afterResponse()`) and log failures instead of rolling back the saved record. They are best-effort: check the log when mail/WhatsApp does not arrive, and remember `.env.example` defaults to `MAIL_MAILER=log` (this checkout uses `smtp`).
- Pengajuan kredit stores text fields as encrypted JSON in `data`, document metadata in `berkas`, and keeps conditional rules in `app/Http/Requests/PengajuanKreditRequest.php`, driven by `config('pengajuan-kredit.produk_usaha' / 'produk_pekerjaan')`. The internal email attaches every document; the applicant only gets the ticket confirmation. NIK masking and the optional admin button are controlled in **Pengaturan Situs** / `PENGAJUAN_KREDIT_LINK_ADMIN`.
- Deployment-specific content, form options, and recipients belong in `config/{beranda,deposito,pengaduan,ppid,pengajuan-kredit,wbs}.php` and `.env`, not in views.
- Shared helpers: `App\Support\Perataan` (text-alignment classes), `App\Support\HtmlDeskripsiProduk` (HTML sanitizer), `App\Models\Concerns\MemilikiPeta` (map coordinates for `KantorCabang`).

## Conventions and gotchas
- **The admin panel returns 403 outside `APP_ENV=local`.** `App\Models\User` does not implement `FilamentUser` and has no `canAccessPanel()`; Filament 5 only permits such users when `config('app.env') === 'local'`. That is why panel tests call `config(['app.env' => 'local'])`. Implement `canAccessPanel()` before any non-local deployment.
- `.env.example` ships `APP_LOCALE=en` (this checkout overrides it to `id`), while views explicitly format Indonesian dates with `->locale('id')->translatedFormat(...)`. Keep that explicit pattern instead of relying on locale.
- The public site does **not** use Vite: `resources/views/layouts/app.blade.php` loads `public/css/style.css` and `public/js/script.js` directly. `resources/css/app.css`, `resources/js/app.js`, and `resources/js/bootstrap.js` are unused leftovers; admin styling goes through `->viteTheme(...)` and requires `npm run build`. `public/build` is gitignored.
- `bootstrap/app.php` redirects web 404s to the referer (or `/`) instead of rendering a 404 page, so missing routes/records look like a normal redirect. Debug via logs/controllers instead of waiting for a 404 screen.
- The active DB is whatever `.env` says. This checkout uses MySQL `bank_bbj`; `.env.example` defaults to SQLite. Neither `database/database.sqlite` nor the root `bank_bbj` file is the live DB.
- `.env.example` covers the credit form and shared `WASENDER_*` keys but omits `PPID_*` / `WBS_*` recipient settings; add those when setting up those forms.
- Beware stray/dead code: `resources/views/welcome.blade.php`, `public/images/web-benner-pop-up.webp`, `public/images/web-benner1.png`, the 0-byte root file `get()`, and the root SQLite file `bank_bbj`. The other `web-benner-*.webp` files are live fallbacks in `config/beranda.php`. `README.md` is the default Laravel readme.
- `/deposito` is `ProdukController::deposito`; flyer images uploaded in admin still show in product sidebars. The old `deposito.flyer` route and `DepositoController::downloadFlyer` are gone, and `barryvdh/laravel-dompdf` is installed but unused.
- `.github/copilot-instructions.md` restates much of this guidance for Copilot — keep the two in sync when editing.
