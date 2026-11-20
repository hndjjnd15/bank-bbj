<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\BannerBerandas\BannerBerandaResource;
use App\Filament\Resources\Beritas\BeritaResource;
use App\Filament\Resources\DokumenPpids\DokumenPpidResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\KantorCabangs\KantorCabangResource;
use App\Filament\Resources\LaporanPelanggarans\LaporanPelanggaranResource;
use App\Filament\Resources\LaporanTahunans\LaporanTahunanResource;
use App\Filament\Resources\LaporanTriwulans\LaporanTriwulanResource;
use App\Filament\Resources\Lelangs\LelangsResource;
use App\Filament\Resources\Lowongans\LowonganResource;
use App\Filament\Resources\PemegangSahams\PemegangSahamResource;
use App\Filament\Resources\Pengaduans\PengaduanResource;
use App\Filament\Resources\PengajuanKredits\PengajuanKreditResource;
use App\Filament\Resources\PengaturanSitus\PengaturanSitusResource;
use App\Filament\Resources\PengurusPerusahaans\PengurusPerusahaanResource;
use App\Filament\Resources\PermohonanInformasis\PermohonanInformasiResource;
use App\Filament\Resources\PopupPromos\PopupPromoResource;
use App\Filament\Resources\ProdukBanks\ProdukBankResource;
use App\Filament\Resources\ProfilPerusahaans\ProfilPerusahaanResource;
use App\Filament\Resources\ProgramCsrs\ProgramCsrResource;
use App\Filament\Resources\SukuBungaDepositos\SukuBungaDepositoResource;
use App\Filament\Resources\VideoYoutubes\VideoYoutubeResource;
use App\Filament\Widgets\AktivitasTerbaru;
use App\Filament\Widgets\AnalitikPortal;
use App\Filament\Widgets\RingkasanDashboard;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Bank BBJ')
            ->brandLogo(asset('images/logobbj.png'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('images/logo-bbj-1x1.ico'))
            ->login(Login::class)
            ->defaultThemeMode(ThemeMode::Light)
            ->darkMode(false)
            ->colors([
                'primary' => Color::Blue,
                'info' => Color::Sky,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                RingkasanDashboard::class,
                AnalitikPortal::class,
                AktivitasTerbaru::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn () => view('filament.auth.login-footer'),
            )
            ->sidebarCollapsibleOnDesktop()   // sidebar bisa dilipat di desktop
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    // Dashboard tetap di paling atas, tanpa grup
                    ->items([
                        ...Dashboard::getNavigationItems(),
                    ])
                    ->groups([
                        // Pengaduan dan permohonan yang masuk dari pengunjung
                        NavigationGroup::make('Kotak Masuk')
                            ->icon(Heroicon::OutlinedInbox)
                            ->collapsed()
                            ->items([
                                ...PengaduanResource::getNavigationItems(),
                                ...PengajuanKreditResource::getNavigationItems(),
                                ...LaporanPelanggaranResource::getNavigationItems(),
                                ...PermohonanInformasiResource::getNavigationItems(),
                            ]),

                        // Sejajar dengan menu "Produk" di website
                        NavigationGroup::make('Produk')
                            ->icon(Heroicon::OutlinedBanknotes)
                            ->collapsed()
                            ->items([
                                ...ProdukBankResource::getNavigationItems(),
                                ...SukuBungaDepositoResource::getNavigationItems(),
                            ]),

                        // Sejajar dengan menu "Informasi" di website
                        NavigationGroup::make('Informasi')
                            ->icon(Heroicon::OutlinedNewspaper)
                            ->collapsed()
                            ->items([
                                ...BeritaResource::getNavigationItems(),
                                ...BannerBerandaResource::getNavigationItems(),
                                ...PopupPromoResource::getNavigationItems(),
                                ...FaqResource::getNavigationItems(),
                                ...LelangsResource::getNavigationItems(),
                                ...LowonganResource::getNavigationItems(),
                                ...DokumenPpidResource::getNavigationItems(),
                                ...VideoYoutubeResource::getNavigationItems(),
                            ]),

                        // Sejajar dengan menu "Tentang kami" di website
                        NavigationGroup::make('Tentang Kami')
                            ->icon(Heroicon::OutlinedBuildingOffice2)
                            ->collapsed()
                            ->items([
                                ...ProfilPerusahaanResource::getNavigationItems(),
                                ...PemegangSahamResource::getNavigationItems(),
                                ...PengurusPerusahaanResource::getNavigationItems(),
                                ...LaporanTriwulanResource::getNavigationItems(),
                                ...LaporanTahunanResource::getNavigationItems(),
                                ...KantorCabangResource::getNavigationItems(),
                                ...ProgramCsrResource::getNavigationItems(),
                            ]),

                        // Nomor WhatsApp dan tampilan situs lainnya
                        NavigationGroup::make('Pengaturan')
                            ->icon(Heroicon::OutlinedCog6Tooth)
                            ->items([
                                ...PengaturanSitusResource::getNavigationItems(),
                            ]),
                    ]);
            });
    }
}
