<?php

namespace Tests\Feature;

use App\Filament\Widgets\AktivitasTerbaru;
use App\Filament\Widgets\AnalitikPortal;
use App\Filament\Widgets\RingkasanDashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_admin_login_form_renders(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Masuk ke Portal Admin')
            ->assertSee('Gunakan akun yang diberikan administrator Bank BBJ.');
    }

    public function test_dashboard_renders_responsive_portal_widgets_for_authenticated_admin(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertOk();

        Livewire::test(RingkasanDashboard::class)
            ->assertSee('Kotak Masuk')
            ->assertSee('Lelang')
            ->assertDontSee('Produk Bank');

        Livewire::test(AnalitikPortal::class)
            ->assertSee('Analitik Portal');

        Livewire::test(AktivitasTerbaru::class)
            ->assertSee('Aktivitas Terbaru')
            ->assertSee('Belum ada aktivitas.');
    }
}
