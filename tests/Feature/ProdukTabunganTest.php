<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\ProdukBank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdukTabunganTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabungan_index_lists_only_published_savings_products(): void
    {
        $this->produkTabungan(['judul' => 'TAKAMUM', 'slug' => 'takamum']);
        $this->produkTabungan(['judul' => 'Tabungan Nonaktif', 'slug' => 'tabungan-nonaktif', 'diterbitkan' => false]);
        ProdukBank::create([
            'jenis' => 'kredit',
            'judul' => 'Kredit',
            'slug' => 'kredit',
            'diterbitkan' => true,
        ]);

        $this->get('/tabungan')
            ->assertOk()
            ->assertSee('TAKAMUM')
            ->assertSee('/tabungan/takamum')
            ->assertDontSee('Tabungan Nonaktif')
            ->assertDontSee('href="/kredit/kredit"');
    }

    public function test_tabungan_detail_uses_rate_schedule_for_target_savings_calculator(): void
    {
        $produk = $this->produkTabungan([
            'judul' => 'TAKAMUM',
            'slug' => 'takamum',
            'simulasi_aktif' => true,
            'persyaratan' => "Fotokopi KTP\nFotokopi KK",
            'keunggulan' => 'Berdomisili di wilayah Bogor',
            'ketentuan' => 'Setoran bulanan sesuai perjanjian',
            'flyer' => ['/images/takamum.webp'],
            'brosur_pdf' => 'produk/takamum.pdf',
            'kontak_whatsapp' => '08123456789',
        ]);
        Faq::create([
            'halaman' => 'tabungan',
            'tanya' => 'Bagaimana cara membuka TAKAMUM?',
            'jawab' => 'Hubungi kantor cabang.',
            'diterbitkan' => true,
        ]);
        $this->produkTabungan(['judul' => 'Tabungan Lain', 'slug' => 'tabungan-lain']);

        $this->get('/tabungan/'.$produk->slug)
            ->assertOk()
            ->assertSee('data-kalkulator-tabungan', false)
            ->assertSee('Persyaratan Berkas')
            ->assertSee('Persyaratan Khusus Produk')
            ->assertSee('Ketentuan Produk')
            ->assertSee('Berdomisili di wilayah Bogor')
            ->assertSee('data-tabungan-target', false)
            ->assertSee('type="text" value="10.000.000" inputmode="numeric"', false)
            ->assertSee('Batas maksimal paket')
            ->assertSee('data-tabungan-batas', false)
            ->assertSee('Rp 100.000.000')
            ->assertSee('data-tabungan-tenor', false)
            ->assertDontSee('data-jadwal=', false)
            ->assertSee('Perkiraan setoran per bulan')
            ->assertSee('Bagaimana cara membuka TAKAMUM?')
            ->assertSee('https://wa.me/628123456789')
            ->assertSee('/images/takamum.webp')
            ->assertSee('takamum.pdf')
            ->assertSee('Produk tabungan lainnya');
    }

    public function test_tabungan_calculator_is_hidden_when_disabled(): void
    {
        $produk = $this->produkTabungan([
            'judul' => 'TAMASYA',
            'slug' => 'tamasya',
            'simulasi_aktif' => false,
        ]);

        $this->get('/tabungan/'.$produk->slug)
            ->assertOk()
            ->assertDontSee('data-kalkulator-tabungan', false)
            ->assertDontSee('Simulasi Tabungan Berjangka')
            ->assertSee('FAQ Tabungan');
    }

    public function test_tapen_detail_marks_its_calculator_for_the_lower_javascript_limit(): void
    {
        $produk = $this->produkTabungan([
            'judul' => 'TAPEN',
            'slug' => 'tapen',
            'simulasi_aktif' => true,
        ]);

        $this->get('/tabungan/'.$produk->slug)
            ->assertOk()
            ->assertSee('data-produk-tabungan="tapen tapen"', false);
    }

    private function produkTabungan(array $overrides = []): ProdukBank
    {
        return ProdukBank::create(array_merge([
            'jenis' => 'tabungan',
            'judul' => 'Produk Tabungan',
            'slug' => 'produk-tabungan',
            'diterbitkan' => true,
            'simulasi_aktif' => false,
        ], $overrides));
    }
}
