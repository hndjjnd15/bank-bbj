<?php

namespace Tests\Feature;

use App\Filament\Resources\SukuBungaDepositos\Pages\CreateSukuBungaDeposito;
use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use App\Models\User;
use Database\Seeders\ProdukDepositoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SukuBungaProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_suku_bunga_for_an_existing_tabungan_product(): void
    {
        config(['app.env' => 'local']);

        $produk = $this->produk('tabungan', 'Tabungan Berjangka', 'tabungan-berjangka');
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateSukuBungaDeposito::class)
            ->fillForm([
                'produk_bank_id' => $produk->id,
                'tenor_bulan' => 12,
                'bunga' => 3.75,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('suku_bunga_depositos', [
            'produk_bank_id' => $produk->id,
            'tenor_bulan' => 12,
            'bunga' => 3.75,
        ]);

        Livewire::test(CreateSukuBungaDeposito::class)
            ->fillForm([
                'produk_bank_id' => $produk->id,
                'tenor_bulan' => 12,
                'bunga' => 4,
            ])
            ->call('create')
            ->assertHasFormErrors(['tenor_bulan' => 'unique']);
    }

    public function test_admin_menu_uses_suku_bunga_produk_label(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs(User::factory()->create())
            ->get('/admin/suku-bunga-depositos')
            ->assertOk()
            ->assertSee('Suku Bunga Produk');
    }

    public function test_rate_schedule_is_limited_to_its_selected_product_on_public_pages(): void
    {
        $deposito = $this->produk('deposito', 'Deposito BBJ', 'deposito-bbj', [
            'setoran_minimal' => 5000000,
        ]);
        $tabungan = $this->produk('tabungan', 'Tabungan BBJ', 'tabungan-bbj', [
            'simulasi_aktif' => true,
        ]);

        SukuBungaDeposito::create([
            'produk_bank_id' => $deposito->id,
            'tenor_bulan' => 6,
            'bunga' => 4.25,
        ]);
        SukuBungaDeposito::create([
            'produk_bank_id' => $tabungan->id,
            'tenor_bulan' => 12,
            'bunga' => 3.75,
        ]);

        $this->get('/deposito')
            ->assertOk()
            ->assertSee('Suku Bunga Deposito BBJ')
            ->assertSee('4,25%')
            ->assertDontSee('3,75%')
            ->assertDontSee('Rentang nominal');

        $this->get('/tabungan/tabungan-bbj')
            ->assertOk()
            ->assertSee('Suku Bunga Tabungan BBJ')
            ->assertSee('3,75%')
            ->assertSee('"tenor":12', false);
    }

    public function test_homepage_shows_rates_for_existing_savings_products(): void
    {
        $deposito = $this->produk('deposito', 'Deposito BBJ', 'deposito-bbj');
        $tabungan = $this->produk('tabungan', 'Tabungan BBJ', 'tabungan-bbj');

        SukuBungaDeposito::create([
            'produk_bank_id' => $deposito->id,
            'tenor_bulan' => 6,
            'bunga' => 4.25,
        ]);
        SukuBungaDeposito::create([
            'produk_bank_id' => $tabungan->id,
            'tenor_bulan' => 12,
            'bunga' => 3.75,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('4,25%')
            ->assertSee('3,75%')
            ->assertSee('Deposito BBJ')
            ->assertSee('Tabungan BBJ');
    }

    public function test_deposito_seeder_creates_a_single_rate_per_tenor(): void
    {
        $this->seed(ProdukDepositoSeeder::class);

        $produkDeposito = ProdukBank::where('slug', 'deposito')->firstOrFail();

        $this->assertSame(3, $produkDeposito->sukuBunga()->count());
        $this->assertDatabaseHas('suku_bunga_depositos', [
            'produk_bank_id' => $produkDeposito->id,
            'tenor_bulan' => 12,
            'bunga' => 6.25,
        ]);
        $this->assertDatabaseHas('suku_bunga_depositos', [
            'produk_bank_id' => $produkDeposito->id,
            'tenor_bulan' => 3,
            'bunga' => 5.75,
        ]);
    }

    private function produk(string $jenis, string $judul, string $slug, array $tambahan = []): ProdukBank
    {
        return ProdukBank::create(array_merge([
            'jenis' => $jenis,
            'judul' => $judul,
            'slug' => $slug,
            'diterbitkan' => true,
        ], $tambahan));
    }
}
