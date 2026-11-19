<?php

namespace Tests\Feature;

use App\Filament\Resources\PengaturanSitus\Pages\EditPengaturanSitus;
use App\Models\PengaturanSitus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WhatsappMengambangTest extends TestCase
{
    use RefreshDatabase;

    public function test_tombol_whatsapp_mengambang_tampil_dengan_nomor_bawaan(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('wa-mengambang', false)
            ->assertSee('https://wa.me/'.PengaturanSitus::WHATSAPP_BAWAAN.'?text=', false);
    }

    public function test_nomor_whatsapp_dari_admin_dinormalkan_menjadi_format_62(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(EditPengaturanSitus::class, ['record' => PengaturanSitus::saatIni()->id])
            ->fillForm([
                'whatsapp' => '0812-3456-7890',
                'whatsapp_pesan' => 'Halo, saya mau bertanya soal tabungan.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get('/')
            ->assertOk()
            ->assertSee('https://wa.me/6281234567890?text=', false)
            ->assertDontSee(PengaturanSitus::WHATSAPP_BAWAAN);
    }

    public function test_admin_dapat_mengatur_sensor_nik_di_email_pengajuan_kredit(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(EditPengaturanSitus::class, ['record' => PengaturanSitus::saatIni()->id])
            ->fillForm([
                'pengajuan_kredit_samarkan_nik' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(PengaturanSitus::saatIni()->fresh()->pengajuan_kredit_samarkan_nik);
    }

    public function test_tombol_whatsapp_mengambang_disembunyikan_saat_dinonaktifkan(): void
    {
        PengaturanSitus::saatIni()->update(['whatsapp_aktif' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('wa-mengambang', false);
    }

    public function test_halaman_pengaturan_situs_dapat_dibuka_admin(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs(User::factory()->create())
            ->get('/admin/pengaturan-situs')
            ->assertOk()
            ->assertSee('Nomor WhatsApp');

        $this->get('/admin/pengaturan-situs/'.PengaturanSitus::saatIni()->id.'/edit')
            ->assertOk()
            ->assertSee('Tombol WhatsApp Mengambang')
            ->assertSee('Privasi Email Pengajuan Kredit');
    }
}
