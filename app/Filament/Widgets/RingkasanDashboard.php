<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Lelangs\LelangsResource;
use App\Filament\Resources\Pengaduans\PengaduanResource;
use App\Models\LaporanPelanggaran;
use App\Models\Lelang;
use App\Models\Pengaduan;
use App\Models\PermohonanInformasi;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanDashboard extends StatsOverviewWidget
{
    protected static ?int $sort = -3;

    protected int|array|null $columns = [
        '@xl' => 2,
        '!@xl' => 1,
    ];

    protected function getStats(): array
    {
        $pengaduan = Pengaduan::query()->count();
        $pelanggaran = LaporanPelanggaran::query()->count();
        $permohonan = PermohonanInformasi::query()->count();

        return [
            Stat::make('Kotak Masuk', $pengaduan + $pelanggaran + $permohonan)
                ->description("{$pengaduan} pengaduan · {$pelanggaran} WBS · {$permohonan} permohonan informasi")
                ->icon(Heroicon::OutlinedInbox)
                ->extraAttributes(['class' => 'dashboard-stat--masuk'])
                ->url(PengaduanResource::getUrl('index')),

            Stat::make('Lelang', Lelang::query()->count())
                ->description('Total jaminan lelang yang tersimpan')
                ->icon(Heroicon::OutlinedMegaphone)
                ->extraAttributes(['class' => 'dashboard-stat--lelang'])
                ->url(LelangsResource::getUrl('index')),
        ];
    }
}
