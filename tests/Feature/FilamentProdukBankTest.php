<?php

namespace Tests\Feature;

use App\Filament\Resources\ProdukBanks\Pages\CreateProdukBank;
use App\Filament\Resources\ProdukBanks\Pages\EditProdukBank;
use App\Models\ProdukBank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentProdukBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_produk_bank_list_renders_for_authenticated_admin(): void
    {
        config(['app.env' => 'local']);

        ProdukBank::create([
            'jenis' => 'kredit',
            'judul' => 'Kredit UMKM',
            'slug' => 'kredit-umkm',
            'diterbitkan' => true,
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/admin/produk-banks')
            ->assertOk()
            ->assertSee('Kredit UMKM');
    }

    public function test_tabungan_with_calculator_enabled_does_not_require_credit_calculator_fields(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs(User::factory()->create());

        Livewire::test(CreateProdukBank::class)
            ->fillForm([
                'jenis' => 'tabungan',
                'judul' => 'Tabungan Berjangka',
                'slug' => 'tabungan-berjangka',
                'deskripsi' => 'Tabungan berjangka.',
                'deskripsi_rata' => 'rata',
                'simulasi_aktif' => true,
                'diterbitkan' => true,
                'foto_utama' => null,
                'flyer' => [],
                'brosur_pdf' => null,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('produk_banks', [
            'jenis' => 'tabungan',
            'slug' => 'tabungan-berjangka',
            'deskripsi' => 'Tabungan berjangka.',
            'simulasi_aktif' => true,
        ]);

        $this->get('/tabungan/tabungan-berjangka')
            ->assertOk()
            ->assertSee('Tabungan berjangka.');
    }

    public function test_existing_tabungan_description_is_loaded_into_the_admin_editor(): void
    {
        config(['app.env' => 'local']);
        $produk = ProdukBank::create([
            'jenis' => 'tabungan',
            'judul' => 'Tabungan Berjangka',
            'slug' => 'tabungan-berjangka',
            'deskripsi' => 'Deskripsi tabungan yang sudah tersimpan.',
            'diterbitkan' => true,
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/admin/produk-banks/'.$produk->id.'/edit')
            ->assertOk()
            ->assertSee('Deskripsi tabungan yang sudah tersimpan.');
    }

    public function test_changing_jenis_from_kredit_to_tabungan_converts_rich_description_to_plain_text(): void
    {
        config(['app.env' => 'local']);

        $produk = ProdukBank::create([
            'jenis' => 'kredit',
            'judul' => 'Kredit UMKM',
            'slug' => 'kredit-umkm',
            'deskripsi' => '<p><strong>Fasilitas</strong> untuk usaha kecil.</p><ul><li>Butuh KTP.</li></ul>',
            'diterbitkan' => true,
        ]);

        $this->actingAs(User::factory()->create());

        Livewire::test(EditProdukBank::class, ['record' => $produk->id])
            ->fillForm(['jenis' => 'tabungan'])
            ->call('save')
            ->assertHasNoFormErrors();

        $deskripsi = ProdukBank::query()->find($produk->id)->deskripsi;

        $this->assertStringNotContainsString('<', $deskripsi);
        $this->assertStringContainsString('Fasilitas untuk usaha kecil.', $deskripsi);
        $this->assertStringContainsString('- Butuh KTP.', $deskripsi);

        $this->get('/tabungan/kredit-umkm')
            ->assertOk()
            ->assertSee('Fasilitas untuk usaha kecil.')
            ->assertDontSee('&lt;p&gt;', false);
    }
}
