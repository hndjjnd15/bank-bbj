<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LaporanPelanggarans\LaporanPelanggaranResource;
use App\Filament\Resources\Lelangs\LelangsResource;
use App\Filament\Resources\Pengaduans\PengaduanResource;
use App\Filament\Resources\PermohonanInformasis\PermohonanInformasiResource;
use App\Models\LaporanPelanggaran;
use App\Models\Lelang;
use App\Models\Pengaduan;
use App\Models\PermohonanInformasi;
use Filament\Widgets\Widget;

class AktivitasTerbaru extends Widget
{
    protected static ?int $sort = -1;

    protected string $view = 'filament.widgets.aktivitas-terbaru';

    protected function getViewData(): array
    {
        $activities = collect()
            ->concat(Pengaduan::query()->latest()->limit(5)->get()->map(fn (Pengaduan $record) => [
                'label' => 'Pengaduan',
                'title' => 'Laporan '.$record->kode,
                'url' => PengaduanResource::getUrl('edit', ['record' => $record]),
                'date' => $record->created_at,
                'tone' => 'blue',
            ]))
            ->concat(LaporanPelanggaran::query()->latest()->limit(5)->get()->map(fn (LaporanPelanggaran $record) => [
                'label' => 'WBS',
                'title' => 'Laporan '.$record->kode,
                'url' => LaporanPelanggaranResource::getUrl('edit', ['record' => $record]),
                'date' => $record->created_at,
                'tone' => 'yellow',
            ]))
            ->concat(PermohonanInformasi::query()->latest()->limit(5)->get()->map(fn (PermohonanInformasi $record) => [
                'label' => 'Permohonan informasi',
                'title' => 'Permohonan '.$record->kode,
                'url' => PermohonanInformasiResource::getUrl('edit', ['record' => $record]),
                'date' => $record->created_at,
                'tone' => 'green',
            ]))
            ->concat(Lelang::query()->latest()->limit(5)->get()->map(fn (Lelang $record) => [
                'label' => 'Lelang',
                'title' => $record->judul,
                'url' => LelangsResource::getUrl('edit', ['record' => $record]),
                'date' => $record->created_at,
                'tone' => 'teal',
            ]))
            ->sortByDesc(fn (array $activity) => $activity['date']?->getTimestamp() ?? 0)
            ->take(6)
            ->values();

        return ['activities' => $activities];
    }
}
