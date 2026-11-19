<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    // Sapaan di atas kartu statistik, mengikuti desain portal admin.
    public function getHeading(): string
    {
        $nama = auth()->user()?->name;

        return 'Selamat datang kembali, '.($nama ?: 'Admin').'!';
    }

    public function getSubheading(): ?string
    {
        return now()->locale('id')->translatedFormat('l, j F Y');
    }
}
