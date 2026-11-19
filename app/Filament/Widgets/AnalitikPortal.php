<?php

namespace App\Filament\Widgets;

use App\Models\LaporanPelanggaran;
use App\Models\Lelang;
use App\Models\Pengaduan;
use App\Models\PermohonanInformasi;
use Filament\Widgets\ChartWidget;

class AnalitikPortal extends ChartWidget
{
    protected static ?int $sort = -2;

    protected ?string $heading = 'Analitik Portal';

    protected ?string $description = 'Jumlah kotak masuk dan lelang dalam enam bulan terakhir.';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $months = collect(range(5, 0))
            ->map(fn (int $offset) => now()->startOfMonth()->subMonths($offset));

        // Kotak masuk menggabungkan pengaduan, WBS, dan permohonan informasi.
        $countByMonth = function (array $models) use ($months): array {
            return $months->map(fn ($month) => collect($models)->sum(fn (string $model): int => $model::query()
                ->whereBetween('created_at', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth(),
                ])
                ->count()))
                ->all();
        };

        return [
            'datasets' => [
                [
                    'label' => 'Kotak masuk',
                    'data' => $countByMonth([
                        Pengaduan::class,
                        LaporanPelanggaran::class,
                        PermohonanInformasi::class,
                    ]),
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Lelang',
                    'data' => $countByMonth([Lelang::class]),
                    'borderColor' => '#18bba1',
                    'backgroundColor' => 'rgba(24, 187, 161, 0.14)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $months
                ->map(fn ($month) => $month->locale('id')->translatedFormat('M'))
                ->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'align' => 'end',
                    'labels' => [
                        'usePointStyle' => true,
                        'boxWidth' => 8,
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
