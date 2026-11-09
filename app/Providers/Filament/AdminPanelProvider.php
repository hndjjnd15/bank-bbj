<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Beritas\BeritaResource;
use App\Filament\Resources\DokumenPpids\DokumenPpidResource;
use App\Filament\Resources\LaporanPelanggarans\LaporanPelanggaranResource;
use App\Filament\Resources\LaporanTahunans\LaporanTahunanResource;
use App\Filament\Resources\LaporanTriwulans\LaporanTriwulanResource;
use App\Filament\Resources\KantorCabangs\KantorCabangResource;
use App\Filament\Resources\Lelangs\LelangsResource;
use App\Filament\Resources\Lowongans\LowonganResource;
use App\Filament\Resources\PemegangSahams\PemegangSahamResource;
use App\Filament\Resources\Pengaduans\PengaduanResource;
use App\Filament\Resources\PengurusPerusahaans\PengurusPerusahaanResource;
use App\Filament\Resources\PermohonanInformasis\PermohonanInformasiResource;
use App\Filament\Resources\ProfilPerusahaans\ProfilPerusahaanResource;
use App\Filament\Resources\ProgramCsrs\ProgramCsrResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Icons\Heroicon;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
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
            ->login()
            ->colors([
                'primary' => Color::Rose,// Ubah warna utama di sini (contoh: Emerald, Blue, Rose)
                'info' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
                                ...LaporanPelanggaranResource::getNavigationItems(),
                                ...PermohonanInformasiResource::getNavigationItems(),
                            ]),
 
                        // Sejajar dengan menu "Informasi" di website
                        NavigationGroup::make('Informasi')
                            ->icon(Heroicon::OutlinedNewspaper)
                            ->collapsed()
                            ->items([
                                ...BeritaResource::getNavigationItems(),
                                ...LelangsResource::getNavigationItems(),
                                ...LowonganResource::getNavigationItems(),
                                ...DokumenPpidResource::getNavigationItems(),
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
                    ]);
            });
    }
}
