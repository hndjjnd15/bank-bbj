<?php

namespace Tests\Feature;

use App\Filament\Resources\PengaturanSitus\Pages\EditPengaturanSitus;
use App\Models\PengaturanSitus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoadingHalamanTest extends TestCase
{
    use RefreshDatabase;

    public function test_animasi_loading_tidak_tampil_secara_bawaan(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('data-loading-halaman', false);
    }

    public function test_animasi_loading_tampil_saat_diaktifkan_dengan_gif(): void
    {
        PengaturanSitus::saatIni()->update([
            'loading_aktif' => true,
            'loading_gif' => 'loading/loader.gif',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-loading-halaman', false)
            ->assertSee('data-loading-durasi="2"', false)
            ->assertSee('/storage/loading/loader.gif', false);
    }

    public function test_durasi_loading_dapat_diatur_dan_dibatasi(): void
    {
        $pengaturan = PengaturanSitus::saatIni();
        $pengaturan->update(['loading_durasi' => 5]);

        $this->assertSame(5, $pengaturan->fresh()->loading_durasi_detik);

        $pengaturan->update(['loading_durasi' => 99]);
        $this->assertSame(15, $pengaturan->fresh()->loading_durasi_detik);
    }

    public function test_admin_dapat_mengatur_durasi_loading(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(EditPengaturanSitus::class, ['record' => PengaturanSitus::saatIni()->id])
            ->fillForm([
                'loading_durasi' => 4,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(4, PengaturanSitus::saatIni()->fresh()->loading_durasi);
    }

    public function test_animasi_loading_tidak_tampil_jika_gif_kosong(): void
    {
        PengaturanSitus::saatIni()->update([
            'loading_aktif' => true,
            'loading_gif' => null,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('data-loading-halaman', false);
    }

    public function test_admin_wajib_mengunggah_gif_saat_animasi_diaktifkan(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(EditPengaturanSitus::class, ['record' => PengaturanSitus::saatIni()->id])
            ->fillForm([
                'loading_aktif' => true,
                'loading_gif' => null,
            ])
            ->call('save')
            ->assertHasFormErrors(['loading_gif' => 'required']);
    }

    public function test_halaman_pengaturan_situs_menampilkan_bagian_animasi_loading(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs(User::factory()->create())
            ->get('/admin/pengaturan-situs/'.PengaturanSitus::saatIni()->id.'/edit')
            ->assertOk()
            ->assertSee('Animasi Loading Halaman')
            ->assertSee('Durasi tampil (detik)');
    }
}
